<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestsResult;
use Illuminate\Http\Request;

class TestsResultController extends Controller
{
  function getTestsWithResults () {
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
}
