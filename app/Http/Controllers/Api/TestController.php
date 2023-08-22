<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    public function getAll () {
        return Test::all();
    }

    public function test (Request $request) {
        $test = Test::where('id', $request->testId)->first();
        if (!$test) {
            abort(404);
        }
        return response()->json(array_merge(
            $test->toArray(),

            ["questions" => $test->questions->map(function ($question) {
                $question['answers'] = $question->answers;
                return $question;
            })]
        ));
    }

    public function create (Request $request) {
        $fields = $request->validate([
            "name" => "required|string",
            "description" => "nullable|string"
        ]);

        $test = Test::create($fields);

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
            "description" => "nullable|string"
        ]);

        $test = Test::where("id", $fields['id'])->first();

        if (!$test) {
            $validator = Validator::make([], []);
            $validator->errors()->add('id', __('validation.custom.test_not_found'));

            return response([
                'errors' => $validator->errors()
            ], 404);
        }

        $updateData = [];

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
