<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseJsonResource;
use App\Models\Result;
use App\Models\ResultAttemptQuestion;
use App\Models\ResultAttemptQuestionAnswer;
use App\Models\Test;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function tests () {
      $tests = Result::query()
        ->groupBy('results.test_id')
        ->select('tests.id', 'tests.name', 'tests.description')
        ->join('tests', 'results.test_id', '=', 'tests.id')
        ->where('results.user_id', auth()->user()->id)
        ->get();

      return new BaseJsonResource($tests);
    }

    public function attempts (Request $request) {
      $attempts = Result::query()
        ->where('user_id', auth()->user()->id)
        ->where('test_id', $request->testId)
        ->orderBy('created_at', 'desc')
        ->orderBy('updated_at', 'desc')
        ->get();

      return new BaseJsonResource($attempts->map(function ($attempt) {
        return [
          'time' => CarbonInterval::createFromFormat('d:h:i:s', $attempt->time)->format('%d дн, %h год, %i хв, %s сек'),
          'attemptId' => $attempt->attempt_id,
          'countQuestions' => $attempt->count_questions,
          'countSuccesses' => $attempt->count_successes,
          'countErrors' => $attempt->count_errors,
          'countMisses' => $attempt->count_misses,
          'percentage' => $attempt->percentage,
          'createdAt' => $attempt->created_at,
          'updatedAt' => $attempt->updated_at,
        ];
      }));
    }

    function attempt (Request $request) {
      $testId = $request->testId;
      $attempt_id = $request->attemptId;

      $tests = ResultAttemptQuestion::query()
        ->where('test_id', $testId)
        ->where('attempt_id', $attempt_id)
        ->orderBy('id', 'asc')
        ->select([
          'id',
          'question_id',
          'answer_id',
          'created_at',
          'updated_at',
          'start_time',
          'end_time'
        ])
        ->with([
          'question' => function ($query) {
            $query
              ->select(['id', 'question', 'description']);
          }
        ])
        ->with([
          'answer' => function ($query) {
            $query
              ->select(['id', 'answer', 'correct']);
          }
        ])
        ->with([
          'answers' => function ($query) {
            $query
              ->select(['answer_id', 'result_attempt_question_id'])
              ->with('answer:id,answer,correct')
              ->orderBy('id', 'asc');
          },
        ])
        ->get();

//      dd($tests);
      $tests = $tests->map(function ($test) {
// $answerUser = $test->question->answers->first(function ($answer) use ($test) {
//  return (string)($answer->id) === (string)($test->answer_id);
// });
// $test->isCorrect = $answerCorrect->isEmpty() ? $answerUser->correct : null;
        $answerCorrect = $test->answers->filter(function (ResultAttemptQuestionAnswer $answer) use ($test) {
          return $answer->answer ? $answer->answer->correct : false;
        });
        $test->answerCorrectText = !$answerCorrect->isEmpty() ? $answerCorrect->pluck('answer.answer')->join(' | ') : null;

        return $test;
      });

//      return new BaseJsonResource($tests);
      return new BaseJsonResource($tests->map(function ($test) {
        $diff = Carbon::parse($test->end_time)->diff(Carbon::parse($test->start_time))->format('%d дн, %h год, %i хв, %s сек');

        return [
          'id' => $test->id,
          'questionId' => $test->question_id,
          'answerId' => $test->answer_id,
          'createdAt' => $test->created_at,
          'updatedAt' => $test->updated_at,
          'startTime' => $test->start_time,
          'endTime' => $test->end_time,
          'diff' => $diff,
          'answer' => $test->answer ? [
            'text' => $test->answer->answer,
            'correct' => $test->answer->correct
          ] : null,
          'answerCorrectText' => $test->answerCorrectText,
          'question' => $test->question ? [
            'text' => $test->question->question,
            'description' => $test->question->description
          ] : null,
          'answers' => $test->answers ? $test->answers->map(function ($answer) {
            return [
              'text' => $answer->answer->answer,
              'correct' => $answer->answer->correct
            ];
          }) : null,
          'time' => ''
        ];
      }));
    }

//    public function create (Request $request) {
//        $fields = $request->validate([
//            'test_id' => 'required|string',
//            'time' => 'required|date',
//            'percentage' => 'required|numeric',
//            'count_questions' => 'required|numeric',
//            'count_errors' => 'required|numeric',
//            'count_successes' => 'required|numeric',
//            'count_misses' => 'required|numeric'
//        ]);
//
//        try {
//            Result::create(array_merge($fields, [
//                "user_id" => auth()->user()->id
//            ]));
//        } catch (\Exception $e) {
//            $response = [
//                'message' => 'An error occurred',
//                'error' => $e->getMessage(),
//                'code' => $e->getCode(),
//            ];
//            return response()->json($response, 500);
//        }
//
//        $response = [
//            'success' => true,
//            'message' => __('messages.result_saved')
//        ];
//
//        return response($response, 201);
//    }

//  function getResultsTests () {
//    $testIds = ResultAttemptQuestion::query()
//      ->select('test_id')
//      ->distinct()
//      ->where('user_id', auth()->user()->id)
//      ->get();
//
//    if (!$testIds->isEmpty()) {
//      $tests = collect();
//
//      $testIds->each(function ($testId) use ($tests) {
//        $test = Test::query()
//          ->where('id', $testId->test_id)
//          ->select('id', 'name')
//          ->first();
//        $tests->push($test);
//      });
//
//      return response()->json($tests);
//    } else {
//      abort(404);
//    }
//  }

//  function getTestResults (Request $request) {
//    $testId = $request->testId;
//
//    $attempts = ResultAttemptQuestion::query()
//      ->distinct()
//      ->select('attempt_id')
//      ->where('user_id', auth()->user()->id)
//      ->where('test_id', $testId)
//      ->distinct()
//      ->pluck('attempt_id');
//
//    return response()->json($attempts);
//  }

//  function getTestAttempt (Request $request) {
//    $testId = $request->testId;
//    $attempt_id = $request->attemptId;
//
//    $tests = ResultAttemptQuestion::query()
//      ->where('test_id', $testId)
//      ->where('attempt_id', $attempt_id)
//      ->with('question', 'question.answers')
//      ->get();
//
//    $tests = $tests->map(function ($test) {
//      $answerUser = $test->question->answers->first(function ($answer) use ($test) {
//        return (string)($answer->id) === (string)($test->answer_id);
//      });
//      $answerCorrect = $test->question->answers->first(function ($answer) use ($test) {
//        return $answer->correct;
//      });
//
//      $test->isCorrect = $answerUser ? $answerUser->correct : null;
//      $test->answerCorrect = $answerCorrect ? $answerCorrect->answer : null;
//
//      return $test;
//    });
//
//    return response()->json($tests);
//  }

  function setAnswer (Request $request) {
    $userId = auth()->user()->id;
    $testId = $request->testId;
    $questionId = $request->questionId;
    $answerId = $request->answerId;
    $attempt_id = $request->attemptId;
    $startTime = date('Y-m-d H:i:s', $request->startTime);
    $endTime = date('Y-m-d H:i:s', $request->endTime);

    $result = ResultAttemptQuestion::query()
      ->where('user_id', $userId)
      ->where('test_id', $testId)
      ->where('question_id', $questionId)
      ->where('attempt_id', $attempt_id)
      ->update([
        'answer_id' => $answerId,
        'start_time' => $startTime,
        'end_time' => $endTime
      ]);

    return response()->json($result ? 'ok' : 'error');
  }
}
