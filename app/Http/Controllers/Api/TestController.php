<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Pagination;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseJsonResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\TestsResult;
use App\Models\TestsResultsAnswers;
use Illuminate\Http\Request;
use App\Models\Test;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Ramsey\Collection\Collection;

class TestController extends Controller
{
    public function index () {
        return Test::all();
    }

    public function test (Request $request) {
      $test = Test::query()->where('id', $request->testId)->first();
      if (!$test) {
          abort(404);
      }

      $search = $request->search ?? '';
      $order = $request->order ?? 'desc';

      $sortedQuestions = $test
        ->questions()
        ->where(function ($query) use ($search) {
          $query->whereRaw('lower(question) COLLATE NOCASE LIKE ?', ['%' . strtolower($search) . '%'])
            ->orWhereHas('answers', function ($subQuery) use ($search) {
              $subQuery->whereRaw('lower(answer) COLLATE NOCASE LIKE ?', ['%' . strtolower($search) . '%']);
            });
        })
        ->orderBy('id', $order)
        ->paginate(150);

      $test->questions = [
        'filters' => [],
        'sorts' => [
          'order' => [
            'param' => 'order',
            'items' => [
              [
                'label' => 'За зменшенням',
                'value' => 'desc',
                'current' => $order === 'desc'
              ],
              [
                'label' => 'За зростанням',
                'value' => 'asc',
                'current' => $order === 'asc'
              ]
            ]
          ]
        ],
        'items' => $sortedQuestions->map(function ($question) {
          $question['answers'] = $question->answers;
          return $question;
        }),
        'pagination' => new Pagination($sortedQuestions)
      ];

      return new BaseJsonResource($test);
    }

    public function testing (Request $request) {
      // $test = Test::where('id', $request->testId)->with(['questionsRandom', 'questionsRandom.answers'])->first();
      $test = Test::query()
        ->where('id', $request->testId)
        ->with(['questions' => function ($query) {
          $query
            ->inRandomOrder()
            ->limit(150);
        }, 'questions.answers' => function  ($query) {
          $query
          ->inRandomOrder(1);
        }])
        ->first();

      if (!$test) {
        abort(404);
      }

      $attempt = Str::uuid()->toString();

      $test->questions->each(function (Question $question) use ($attempt) {
        $user_id = $question->user_id;
        $test_id = $question->test_id;
        $question_id = $question->id;

        $testsResult = TestsResult::query()->create([
          'attempt' => $attempt,
          'user_id' => $user_id,
          'test_id' => $test_id,
          'question_id' => $question_id
        ]);

        $question->answers->each(function (Answer $answer) use ($testsResult) {
          TestsResultsAnswers::query()->create([
            'answer_id' => $answer->id,
            'tests_results_id' => $testsResult->id
          ]);
        });
      });

      return response()->json($test);
    }

    public function create (Request $request) {
        $fields = $request->validate([
            "name" => "required|string",
            "description" => "nullable|string|max:255"
        ]);

        $test = Test::create([
            "name" => $fields['name'],
            "description" => $fields['description'],
            "user_id" => auth()->user()->id
        ]);

        $response = [
            'success' => true,
            'message' => __('messages.test_created'),
            'test' => $test
        ];

        return response($response, 201);
    }

    public function update (Request $request) {
        $fields = $request->validate([
            "id" => "required",
            "name" => "string",
            "description" => "nullable|string|max:255"
        ]);

        $test = Test::where("id", $fields['id'])->first();

        if (!$test) {
            $validator = Validator::make([], []);
            $validator->errors()->add('id', __('validation.custom.test_not_found'));

            return response([
                'errors' => $validator->errors()
            ], 404);
        }

        $updateData = [
            "user_id" => auth()->user()->id
        ];

        if (isset($fields['name'])) {
            $updateData['name'] = $fields['name'];
        }

        if (isset($fields['description']) || $fields['description'] === null) {
            $updateData['description'] = $fields['description'];
        }

        $test->update($updateData);

        $response = [
            'success' => true,
            'message' => __('messages.test_updated'),
            'test' => $test
        ];

        return response($response);
    }

    public function delete (Request $request) {
        $fields = $request->validate([
            "id" => "required"
        ]);

        $test = Test::where("id", $fields['id'])->first();

        if (!$test) {
            $validator = Validator::make([], []);
            $validator->errors()->add('id', __('validation.custom.test_not_found'));

            return response([
                'errors' => $validator->errors()
            ], 404);
        }

        $test->delete();

        $response = [
            'success' => true,
            'message' => __('messages.test_delete'),
            'test' => $test
        ];

        return response($response);
    }
}
