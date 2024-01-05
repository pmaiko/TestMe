<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseJsonResource;
use App\Models\Answer;
use App\Models\Favorite;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    public function index () {
        $favorites = Favorite::where("user_id", auth()->user()->id)->get();

        $questions = [];
        foreach ($favorites as $favorite) {
            $question = Question::where("id", $favorite->question_id)->first();
            $answers = Answer::where("question_id", $favorite->question_id)->where("test_id", $question->test_id)->get();

            $question['favorite_id'] = $favorite->id;
            $question['answers'] = $answers;
            $questions[] = $question;
        }

        return new BaseJsonResource($questions);
//        return response($response, 201);
    }

    public function create (Request $request) {
        $fields = $request->validate([
            'id' => 'required'
        ]);

        try {
            Favorite::create([
                "user_id" => auth()->user()->id,
                "question_id" => $fields['id']
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
            'message' => __('messages.favorite_question_added')
        ];

        return new BaseJsonResource($response);
    }

    public function delete (Request $request) {
        $fields = $request->validate([
            'id' => 'required'
        ]);

        $favorite = Favorite::where("question_id", $fields['id'])->where('user_id', auth()->user()->id)->delete();

        if (!$favorite) {
            $validator = Validator::make([], []);
            $validator->errors()->add('id', __('validation.custom.favorite_not_found'));

            return response([
                'errors' => $validator->errors()
            ], 404);
        }

        $response = [
            'message' => __('messages.favorite_question_deleted')
        ];

      return new BaseJsonResource($response);
    }
}
