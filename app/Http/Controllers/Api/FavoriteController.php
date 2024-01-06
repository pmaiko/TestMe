<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\QuestionResource;
use App\Models\Answer;
use App\Models\Favorite;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    public function index () {
        $favorites = Favorite::query()
          ->where('user_id', auth()->user()->id)
          ->orderBy('updated_at', 'desc')
          ->with(['question', 'question.answers' => function ($query) {
            $query->select(['question_id', 'id', 'answer', 'description', 'correct']);
          }])
          ->get();

        return new BaseJsonResource($favorites->map(function ($favorite) {
          return [
            'question' => new QuestionResource($favorite->question),
            'createdAt' => $favorite->created_at,
            'updatedAt' => $favorite->updated_at,
          ];
        }));
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
