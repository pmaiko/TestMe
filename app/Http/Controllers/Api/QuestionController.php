<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function Psy\debug;

class QuestionController extends Controller
{
    public function create (Request $request) {
        $fields = $request->validate([
            "test_id" => "required|string",
            "question" => "required|string",
            "description" => "string",

            "answers" => "required|array",
            "answers.*.answer" => "required|string",
            "answers.*.description" => "string",
            "answers.*.correct" => "boolean",
        ]);

        try {
            DB::beginTransaction();
            $question = Question::create([
                "test_id" => $fields['test_id'],
                "question" => $fields['question'],
                "description" => $fields['description']
            ]);

            $answers = [];
            foreach ($fields['answers'] as $answer) {
                $answers[] = Answer::create(array_merge($answer, [
                    "test_id" => $fields["test_id"],
                    "question_id" => $question->id
                ]));
            }
            DB::commit();

            $question['answers'] = $answers;

            $response = [
                'success' => true,
                'message' => __('messages.question_created'),
                'question' => $question
            ];

            return response($response, 201);
        } catch (\Exception $e) {
            DB::rollback();
            $errorResponse = [
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
            return response()->json($errorResponse, 500);
        }
    }

    public function update (Request $request) {
        $fields = $request->validate([
            "test_id" => "required|string",
            "question_id" => "required|string",

            "question" => "string",
            "description" => "string",

            "answers" => "array|nullable",
            "answers.*.answer_id" => "string",
            "answers.*.answer" => "required_without:answers.*.answer_id|string",
            "answers.*.description" => "string",
            "answers.*.correct" => "boolean",
//            "answers.*.answer" => "required_with:answers.*.answer_id|string", // Обязательное поле, если answer_id присутствует
        ]);

        $question = Question::where("id", $fields['question_id'])->where("test_id", $fields["test_id"])->first();

        if (!$question) {
            $validator = Validator::make([], []);
            $validator->errors()->add('question_id', __('validation.custom.question_not_found'));

            return response([
                'errors' => $validator->errors()
            ], 404);
        }

        $updateQuestionData = [];

        if (isset($fields['question'])) {
            $updateQuestionData['question'] = $fields['question'];
        }
        if (isset($fields['description'])) {
            $updateQuestionData['description'] = $fields['description'];
        }
        $question->update($updateQuestionData);

        $answers = [];
        $answersDeleted = false;
        if ($fields['answers']) {
            foreach ($fields['answers'] as $answer) {
                if (isset($answer['answer_id'])) {
                    $updateAnswerData = [];
                    $answerDB = Answer::where("id", $answer['answer_id'])->
                        where("question_id", $fields['question_id'])->
                        where("test_id", $fields["test_id"])->
                        first();

                    if (!$answerDB) {
                        $validator = Validator::make([], []);
                        $validator->errors()->add('answer_id', __('validation.custom.answer_not_found'));

                        return response([
                            'errors' => $validator->errors()
                        ], 404);
                    }

                    if (isset($answer['answer'])) {
                        $updateAnswerData['answer'] = $answer['answer'];
                    }
                    if (isset($answer['description'])) {
                        $updateAnswerData['description'] = $answer['description'];
                    }
                    if (isset($answer['correct'])) {
                        $updateAnswerData['correct'] = $answer['correct'];
                    }

                    $answerDB->update($updateAnswerData);
                    $answers[] = $answerDB;
                } else {
                    $answerDB = Answer::create([
                        "test_id" => $fields['question_id'],
                        "question_id" => $fields["test_id"],
                        "answer" => $answer["answer"],
                        "description" => $answer["description"] ?? null,
                        "correct" => $answer["correct"] ?? false,
                    ]);
                    $answers[] = $answerDB;
                }
            }
        } else {
            Answer::where('question_id', $fields["question_id"])->where('test_id', $fields["test_id"])->delete();
            $answersDeleted = true;
        }

        $question['answers'] = $answers;

        $response = [
            'success' => true,
            'message' => __('messages.test_updated') . ($answersDeleted ? ". " . __('messages.answers_all_deleted') : ""),
            'question' => $question
        ];

        return response($response);
    }

    public function delete (Request $request) {
        $fields = $request->validate([
            "test_id" => "required|string",
            "question_id" => "required|string"
        ]);

        $question = Question::where("id", $fields['question_id'])->where("test_id", $fields["test_id"])->first();
        $answer = Answer::where('question_id', $fields["question_id"])->where('test_id', $fields["test_id"]);

        if (!$question) {
            $validator = Validator::make([], []);
            $validator->errors()->add('question_id', __('validation.custom.question_not_found'));

            return response([
                'errors' => $validator->errors()
            ], 404);
        }

        if (!$answer) {
            $validator = Validator::make([], []);
            $validator->errors()->add('question_id', __('validation.custom.answer_not_found'));

            return response([
                'errors' => $validator->errors()
            ], 404);
        }

        $question->delete();
        $answer->delete();

        $response = [
            'success' => true,
            'message' => __('messages.test_delete') . ". " . __('messages.answers_all_deleted')
        ];

        return response($response);
    }
}
