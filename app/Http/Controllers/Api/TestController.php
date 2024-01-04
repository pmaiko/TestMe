<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Pagination;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseJsonResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Result;
use App\Models\ResultAttempt;
use App\Models\ResultAttemptQuestion;
use App\Models\ResultAttemptQuestionAnswer;
use Carbon\Carbon;
use Carbon\CarbonInterval;
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
//        ->where(function ($query) use ($search) {
//          $query->whereRaw('lower(question) COLLATE NOCASE LIKE ?', ['%' . strtolower($search) . '%'])
//            ->orWhereHas('answers', function ($subQuery) use ($search) {
//              $subQuery->whereRaw('lower(answer) COLLATE NOCASE LIKE ?', ['%' . strtolower($search) . '%']);
//            });
//        })
        ->where(function ($query) use ($search) {
          $query->where(DB::raw('lower(question)'), 'like', '%' . strtolower($search) . '%')
            ->orWhereHas('answers', function ($subQuery) use ($search) {
              $subQuery->where(DB::raw('lower(answer)'), 'like', '%' . strtolower($search) . '%');
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

      // $attempt = Str::uuid()->toString();

      $user_id = auth()->user()->id;
      $test_id = $test->id;

      $attempt = ResultAttempt::query()->create([
        'test_id' => $test_id,
        'user_id' => $user_id
      ]);

      $attempt_id = $attempt->id;

      $test->questions->each(function (Question $question) use ($attempt_id, $test_id, $user_id) {
        $question_id = $question->id;

        $testsResult = ResultAttemptQuestion::query()->create([
          'attempt_id' => $attempt_id,
          'test_id' => $test_id,
          'question_id' => $question_id,
          'user_id' => $user_id,
        ]);

        $question->answers->each(function (Answer $answer) use ($testsResult) {
          ResultAttemptQuestionAnswer::query()->create([
            'answer_id' => $answer->id,
            'result_attempt_question_id' => $testsResult->id
          ]);
        });
      });

      $test->attemptId = $attempt_id;

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

    public function complete (Request $request) {
      $fields = $request->validate([
        'attemptId' => 'required'
      ]);

      $attemptId = $request->attemptId;
      $testId = $request->testId;
      $userId = auth()->user()->id;

      $items = ResultAttemptQuestion::query()
        ->where('attempt_id', $attemptId)
        ->where('test_id', $testId)
        ->where('user_id', auth()->user()->id)
        ->with('answer')
        ->get();

      $time = CarbonInterval::seconds();
      $countQuestions = $items->count();
      $countErrors = null;
      $countSuccesses = null;
      $countMisses = null;

      $items->each(function ($item) use (&$time, &$countErrors, &$countSuccesses, &$countMisses) {
        $diff = Carbon::parse($item->end_time)->diff(Carbon::parse($item->start_time));
        $time = $time->add($diff);

        if (!$item->answer) {
          $countMisses++;
        } else if ($item->answer->correct) {
          $countSuccesses++;
        } else {
          $countErrors++;
        }
      });

      $percentage = ($countSuccesses / $countQuestions) * 100;

      $result = Result::query()->create([
        'time' => $time->format('%D:%H:%I:%S'),
        'percentage' => round($percentage, 2),
        'count_questions' => $countQuestions,
        'count_errors' => $countErrors,
        'count_successes' => $countSuccesses,
        'count_misses' => $countMisses,
        'attempt_id' => $attemptId,
        'test_id' => $testId,
        'user_id' => $userId,
      ]);

      return new BaseJsonResource([
        'attemptId' => $result->attempt_id,
        'time' => CarbonInterval::createFromFormat('d:h:i:s', $result->time)->format('%d дн, %h год, %i хв, %s сек'),
        'countSuccesses' => $result->count_successes ?? 0,
        'countErrors' => $result->count_errors ?? 0,
        'countMisses' => $result->count_misses ?? 0
      ]);
    }
}
