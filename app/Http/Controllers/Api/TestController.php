<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Pagination;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\QuestionCollection;
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
        ->with(['answers' => function ($query) {
          $query->select(['question_id', 'id', 'answer', 'description', 'correct']);
        }])
        ->select(['test_id', 'id', 'question', 'description', 'created_at', 'updated_at'])
        ->where(function ($query) use ($search) {
          $query->where(DB::raw('lower(question)'), 'like', '%' . strtolower($search) . '%')
            ->orWhereHas('answers', function ($subQuery) use ($search) {
              $subQuery->where(DB::raw('lower(answer)'), 'like', '%' . strtolower($search) . '%');
            });
        })
        ->orderBy('id', $order)
        // ->orderBy('updated_at', $order)
        ->paginate(150);

      $test->questions = [
        'filters' => [],
        'sorts' => [
          'order' => [
            'param' => 'order',
            'items' => [
              [
                'label' => __('messages.desc'),
                'value' => 'desc',
                'current' => $order === 'desc'
              ],
              [
                'label' => __('messages.asc'),
                'value' => 'asc',
                'current' => $order === 'asc'
              ]
            ]
          ]
        ],
        'items' => new QuestionCollection($sortedQuestions),
        'pagination' => new Pagination($sortedQuestions)
      ];

      return new BaseJsonResource($test);
    }

    public function testing (Request $request) {
      $fields = $request->validate([
        'countQuestions' =>  ['regex:/^(?:\d+|all)$/'],
      ]);

      $limit = Str::lower($request->countQuestions) === Str::lower('all') ? null : $request->countQuestions ?? 150;

      $test = Test::query()
        ->where('id', $request->testId)
        ->with(['questions' => function ($query) use ($limit) {
          $query
            ->inRandomOrder()
            ->limit($limit);
        }, 'questions.answers' => function  ($query) {
          $query
          ->select(['question_id', 'id', 'answer', 'description', 'correct'])
          ->inRandomOrder();
        }])
        ->first();

      if (!$test) {
        abort(404);
      }

      $userId = auth()->user()->id;
      $testId = $test->id;

      $attempt = ResultAttempt::query()->create([
        'test_id' => $testId,
        'user_id' => $userId
      ]);

      $attemptId = $attempt->id;

      // $test->questions->each(function (Question $question) use ($attemptId, $testId, $userId) {
      //   $question_id = $question->id;
      //
      //   $resultAttemptQuestion = ResultAttemptQuestion::query()->create([
      //     'attempt_id' => $attemptId,
      //     'test_id' => $testId,
      //     'question_id' => $question_id,
      //     'user_id' => $userId,
      //   ]);
      //
      //   $question->answers->each(function (Answer $answer) use ($resultAttemptQuestion, $attemptId) {
      //     ResultAttemptQuestionAnswer::query()->create([
      //       'attempt_id' => $attemptId,
      //       'answer_id' => $answer->id,
      //       'result_attempt_question_id' => $resultAttemptQuestion->id
      //     ]);
      //   });
      // });

      $resultAttemptQuestionsData = [];
      $test->questions->each(function (Question $question) use (&$resultAttemptQuestionsData, $attemptId, $testId, $userId) {
        $question_id = $question->id;

        $resultAttemptQuestionsData[] = [
          'attempt_id' => $attemptId,
          'test_id' => $testId,
          'question_id' => $question_id,
          'user_id' => $userId,
          'created_at' => now(),
          'updated_at' => now()
        ];
      });

      $resultAttemptQuestionsDataChunk = array_chunk($resultAttemptQuestionsData, 900);
      foreach ($resultAttemptQuestionsDataChunk as $chunk) {
        ResultAttemptQuestion::query()->insert($chunk);
      }

      $resultAttemptQuestions = ResultAttemptQuestion::query()
        ->where('attempt_id', $attemptId)
        ->where('test_id', $testId)
        ->where('user_id', $userId)
        ->select('id', 'question_id')
        ->get();

      $resultAttemptQuestionAnswersData = [];
      $test->questions->each(function (Question $question) use (&$resultAttemptQuestionAnswersData, $resultAttemptQuestions, $attemptId) {
        $questionId = $question->id;

        $finedId = optional($resultAttemptQuestions->first(function ($resultAttemptQuestion) use ($questionId) {
          return (string)($resultAttemptQuestion->question_id) === (string)($questionId);
        }))->id;

        if (isset($finedId)) {
          $question->answers->each(function (Answer $answer) use (&$resultAttemptQuestionAnswersData, $finedId, $attemptId) {
            $resultAttemptQuestionAnswersData[] = [
              'attempt_id' => $attemptId,
              'answer_id' => $answer->id,
              'result_attempt_question_id' => $finedId,
              'created_at' => now(),
              'updated_at' => now()
            ];
          });
        }
      });

      $resultAttemptQuestionAnswersDataChunk = array_chunk($resultAttemptQuestionAnswersData, 900);
      foreach ($resultAttemptQuestionAnswersDataChunk as $chunk) {
        ResultAttemptQuestionAnswer::query()->insert($chunk);
      }

      return new BaseJsonResource([
        'questions' => new QuestionCollection($test->questions),
        'attemptId' => $attemptId,
        'id' => $testId,
        'name' => $test->name,
        'description' => $test->description,
        'userId' => $test->user_id,
        'createdAt' => $test->created_at,
        'updatedAt' => $test->updated_at,
      ]);

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

      ResultAttempt::query()
        ->where('test_id', $testId)
        ->where('user_id', $userId)
        ->update([
          'completed' => true
        ]);

      $result = Result::query()->create([
        'time' => $time->cascade()->format('%D:%H:%I:%S'),
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
        'time' => CarbonInterval::createFromFormat('d:h:i:s', $result->time)->format('%H:%I:%S'),
        'percentage' => $result->percentage ?? 0,
        'countSuccesses' => $result->count_successes ?? 0,
        'countErrors' => $result->count_errors ?? 0,
        'countMisses' => $result->count_misses ?? 0
      ]);
    }
}


// ->where(function ($query) use ($search) {
//  $query->whereRaw('lower(question) COLLATE NOCASE LIKE ?', ['%' . strtolower($search) . '%'])
//    ->orWhereHas('answers', function ($subQuery) use ($search) {
//      $subQuery->whereRaw('lower(answer) COLLATE NOCASE LIKE ?', ['%' . strtolower($search) . '%']);
//    });
// })