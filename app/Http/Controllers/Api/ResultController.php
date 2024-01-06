<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerCollection;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\QuestionResource;
use App\Models\Result;
use App\Models\ResultAttemptQuestion;
use App\Models\ResultAttemptQuestionAnswer;
use App\Models\Test;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class ResultController extends Controller {
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
        'time' => CarbonInterval::createFromFormat('d:h:i:s', $attempt->time)->format('%H:%I:%S'),
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

    $results = ResultAttemptQuestion::query()
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
            ->select(['id', 'answer', 'correct', 'description']);
        }
      ])
      ->with([
        'answers' => function ($query) {
          $query
            ->select(['answer_id', 'result_attempt_question_id'])
            ->with('answer:id,answer,correct,description')
            ->orderBy('id', 'asc');
        },
      ])
      ->get();

//    $results = $results->map(function ($question) {
  // $answerUser = $test->question->answers->first(function ($answer) use ($test) {
  //  return (string)($answer->id) === (string)($test->answer_id);
  // });
  // $test->isCorrect = $answerCorrect->isEmpty() ? $answerUser->correct : null;
  // $test->answerCorrectText = !$answerCorrect->isEmpty() ? $answerCorrect->pluck('answer.answer')->join(' | ') : null;

//      $answerCorrect = $question->answers
//        ->filter(function (ResultAttemptQuestionAnswer $answer) use ($question) {
//        return $answer->answer ? $answer->answer->correct : false;
//      })->map(function ($answer) {
//        return $answer->answer;
//      })->values();
//      $question->answerCorrect = !$answerCorrect->isEmpty() ? $answerCorrect : null;

//      return $question;
//    });

    return new BaseJsonResource($results->map(function ($result) {
      $diff = Carbon::parse($result->end_time)->diff(Carbon::parse($result->start_time))->format('%H:%I:%S');

      return [
        'question' => new QuestionResource($result->question),
        'answers' => new AnswerCollection($result->answers->map(function ($answer) {
          return $answer->answer;
        })),
        'answer' => new AnswerResource($result->answer),
        'answerId' => $result->answer_id,
        'testId' => $result->id,
        'createdAt' => $result->created_at,
        'updatedAt' => $result->updated_at,
        'startTime' => $result->start_time,
        'endTime' => $result->end_time,
        'diff' => $diff,
      ];
    }));
  }


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
