<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestsResult;
use Illuminate\Http\Request;

class TestsResultController extends Controller
{
  function getResultsTests () {
    $testIds = TestsResult::query()
      ->select('test_id')
      ->distinct()
      ->where('user_id', auth()->user()->id)
      ->get();

    if (!$testIds->isEmpty()) {
      $tests = collect();

      $testIds->each(function ($testId) use ($tests) {
        $test = Test::query()
          ->where('id', $testId->test_id)
          ->select('id', 'name')
          ->first();
        $tests->push($test);
      });

      return response()->json($tests);
    } else {
      abort(404);
    }
  }

  function getTestResults (Request $request) {
    $testId = $request->testId;

    $attempts = TestsResult::query()
      ->distinct()
      ->select('attempt')
      ->where('user_id', auth()->user()->id)
      ->where('test_id', $testId)
      ->distinct()
      ->pluck('attempt');

    return response()->json($attempts);
  }

  function getTestAttempt (Request $request) {
    $testId = $request->testId;
    $attempt = $request->attempt;

    $tests = TestsResult::query()
      ->where('test_id', $testId)
      ->where('attempt', $attempt)
      ->with('question', 'question.answers')
      ->get();

    $tests = $tests->map(function ($test) {
      $answerUser = $test->question->answers->first(function ($answer) use ($test) {
        return (string)($answer->id) === (string)($test->answer_id);
      });
      $answerCorrect = $test->question->answers->first(function ($answer) use ($test) {
        return $answer->correct;
      });
      $test->isCorrect = $answerUser->correct;
      $test->answerCorrect = $answerCorrect->answer;
      return $test;
    });

    return response()->json($tests);
  }

  function setAnswer (Request $request) {
    $userId = auth()->user()->id;
    $testId = $request->testId;
    $questionId = $request->questionId;
    $answerId = $request->answerId;
    $attempt = $request->attempt;
    $startTime = date('Y-m-d H:i:s', $request->startTime);
    $endTime = date('Y-m-d H:i:s', $request->endTime);

    $result = TestsResult::query()
      ->where('user_id', $userId)
      ->where('test_id', $testId)
      ->where('question_id', $questionId)
      ->where('attempt', $attempt)
      ->update([
        'answer_id' => $answerId,
        'start_time' => $startTime,
        'end_time' => $endTime
      ]);

    return response()->json($result ? 'ok' : 'error');
  }
}
