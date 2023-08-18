<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Favorite;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    public function getAll () {
        $favorites = Favorite::where("user_id", auth()->user()->id)->get();

        $questions = [];
        foreach ($favorites as $favorite) {
            $question = Question::where("id", $favorite->question_id)->first();
            $answers = Answer::where("question_id", $favorite->question_id)->where("test_id", $question->test_id)->get();

            $question['favorite_id'] = $favorite->id;
            $question['answers'] = $answers;
            $questions[] = $question;
        }

        $response = [
            'success' => true,
            'favorites' => $questions
        ];

        return response($response, 201);
    }

    public function create (Request $request) {
        $fields = $request->validate([
            'question_id' => 'required|string'
        ]);

        try {
            Favorite::create([
                "user_id" => auth()->user()->id,
                "question_id" => $fields['question_id']
            ]);
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
            'message' => __('messages.favorite_question_added')
        ];

        return response($response, 201);
    }

    public function delete (Request $request) {
        $fields = $request->validate([
            'id' => 'required|string|numeric'
        ]);

        $favorite = Favorite::where("id", $fields['id'])->where('user_id', auth()->user()->id)->delete();

        if (!$favorite) {
            $validator = Validator::make([], []);
            $validator->errors()->add('id', __('validation.custom.favorite_not_found'));

            return response([
                'errors' => $validator->errors()
            ], 404);
        }

        $response = [
            'success' => true,
            'message' => __('messages.favorite_question_deleted')
        ];

        return response($response, 201);
    }
}
