<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Test;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index () {
//        $results = Result::where("user_id", auth()->user()->id)->get();
//        'results' => $results->map(function ($result) {
//            $result['test'] = $result->test;
//            return $result;
//        })

        $tests = Test::all();

        $response = [
            'success' => true,
            'tests' => $tests->map(function ($test) {
                $results = Result::where("user_id", auth()->user()->id)
                    ->where("test_id", $test->id)
                    ->get();

                $test['results'] = $results;
                return $test;
            })
        ];

        return response($response, 201);
    }

    public function create (Request $request) {
        $fields = $request->validate([
            'test_id' => 'required|string',
            'time' => 'required|date',
            'percentage' => 'required|numeric',
            'count_questions' => 'required|numeric',
            'count_errors' => 'required|numeric',
            'count_successes' => 'required|numeric',
            'count_misses' => 'required|numeric'
        ]);

        try {
            Result::create(array_merge($fields, [
                "user_id" => auth()->user()->id
            ]));
        } catch (\Exception $e) {
            $response = [
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
            return response()->json($response, 500);
        }

        $response = [
            'success' => true,
            'message' => __('messages.result_saved')
        ];

        return response($response, 201);
    }
}
