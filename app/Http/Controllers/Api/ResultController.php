<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerCollection;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\QuestionResource;
use App\Models\Result;
use App\Models\ResultAttempt;
use App\Models\ResultAttemptQuestion;
use App\Models\ResultAttemptQuestionAnswer;
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

  public function dashboard (Request $request) {
    $data = Result::query()
      ->where('user_id', auth()->user()->id)
      ->where('test_id', $request->testId)
      ->get();

    $count = $data->count();
    $time = CarbonInterval::seconds();
    $timeMin = null;

    $countQuestions = null;

    $countSuccesses = null;
    $countMinSuccesses = null;
    $countMaxSuccesses = null;

    $countErrors = null;
    $countMinErrors = null;
    $countMaxErrors = null;

    $countMisses = null;
    $countMinMisses = null;
    $countMaxMisses = null;

    $percentage = null;
    $percentageMin = null;
    $percentageMax = null;

    $data->each(function ($item) use (
      &$time,
      &$timeMin,

      &$countQuestions,

      &$countSuccesses,
      &$countMinSuccesses,
      &$countMaxSuccesses,

      &$countErrors,
      &$countMinErrors,
      &$countMaxErrors,

      &$countMisses,
      &$countMinMisses,
      &$countMaxMisses,

      &$percentage,
      &$percentageMin,
      &$percentageMax
    ) {
      $formattedTime  = CarbonInterval::createFromFormat('d:h:i:s', $item->time);
      $time->add($formattedTime);
      if (!$timeMin) {
        $timeMin = $formattedTime;
      } else {
        $timeMinSec = $timeMin->total('seconds');
        $formattedTimeSec = $formattedTime->total('seconds');
        $timeMin = $timeMinSec < $formattedTimeSec ? $timeMin : $formattedTime;
      }

      $countQuestions = max($countQuestions, $item->count_questions);

      $countSuccesses+= $item->count_successes;
      $countMinSuccesses = min($countMinSuccesses, $item->count_successes);
      $countMaxSuccesses = max($countMaxSuccesses, $item->count_successes);

      $countErrors+= $item->count_errors;
      $countMinErrors = min($countMinErrors, $item->count_errors);
      $countMaxErrors = max($countMaxErrors, $item->count_errors);

      $countMisses+= $item->count_misses;
      $countMinMisses = min($countMinMisses, $item->count_misses);
      $countMaxMisses = max($countMaxMisses, $item->count_misses);

      $percentage+= $item->percentage;
      $percentageMin = min($percentageMin, $item->percentage);
      $percentageMax = max($percentageMax, $item->percentage);
    });

    return new BaseJsonResource([
      [
        'count' => [
          'label' => 'Кількість спроб',
          'value' => $count
        ],
      ],
      [
        'countQuestions' => [
          'label' => 'Кількість питань',
          'value' => $countQuestions
        ],
      ],
      [
        'timeMin' => [
          'label' => 'Найменший час виконання',
          'value' => $timeMin->cascade()->format('%dдн. %hгод. %iхв. %sсек.')
        ],
      ],
      [
        'time' => [
          'label' => 'Середній час виконання',
          'value' => $time->divide($count)->cascade()->format('%dдн. %hгод. %iхв. %sсек.')
        ],
      ],
      // [
      //   'countMinSuccesses' => [
      //     'label' => 'Найменше успішних відповідей',
      //     'value' => $countMinSuccesses ?? 0
      //   ],
      // ],
      [
        'countMaxSuccesses' => [
          'label' => 'Найбільше успішних відповідей',
          'value' => $countMaxSuccesses ?? 0
        ],
      ],
      [
        'countSuccesses' => [
          'label' => 'Середня кількість успішних відповідей',
          'value' => round($countSuccesses / $count, 2)
        ],
      ],

      // [
      //   'countMinErrors' => [
      //     'label' => 'Найменше помилок',
      //     'value' => $countMinErrors ?? 0
      //   ],
      // ],
      [
        'countMinErrors' => [
          'label' => 'Найбільше помилок',
          'value' => $countMaxErrors ?? 0
        ],
      ],
      [
        'countErrors' => [
          'label' => 'Середня кількість помилок',
          'value' => round($countErrors / $count, 2)
        ],
      ],

      // [
      //   'countMinMisses' => [
      //     'label' => 'Найменше пропусків',
      //     'value' => $countMinMisses ?? 0
      //   ],
      // ],
      [
        'countMaxMisses' => [
          'label' => 'Найбільше пропусків',
          'value' => $countMaxMisses ?? 0
        ],
      ],
      [
        'countMisses' => [
          'label' => 'Середня кількість пропусків',
          'value' => round($countMisses / $count, 2)
        ],
      ],

      // [
      //   'percentageMin' => [
      //     'label' => 'Найменший відсоток',
      //     'value' => ($percentageMin ?? 0) . '%'
      //   ]
      // ],
      [
        'percentageMax' => [
          'label' => 'Найбільший відсоток',
          'value' => ($percentageMax ?? 0) . '%'
        ]
      ],
      [
        'percentage' => [
          'label' => 'Середній відсоток',
          'value' => round($percentage / $count, 2) . '%'
        ]
      ]
    ]);
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

    // $results = $results->map(function ($result) {
    //  $answerUser = $result->question->answers->first(function ($answer) use ($test) {
    //    return (string)($answer->id) === (string)($test->answer_id);
    //  });
    //  $test->isCorrect = $answerCorrect->isEmpty() ? $answerUser->correct : null;
    //  $test->answerCorrectText = !$answerCorrect->isEmpty() ? $answerCorrect->pluck('answer.answer')->join(' | ') : null;
    //
    //  $answerCorrect = $result->answers
    //      ->filter(function (ResultAttemptQuestionAnswer $answer) use ($result) {
    //      return $answer->answer ? $answer->answer->correct : false;
    //    })->map(function ($answer) {
    //      return $answer->answer;
    //    })->values();
    //  $result->answerCorrect = !$answerCorrect->isEmpty() ? $answerCorrect : null;
    //
    //    return $result;
    // });

    return new BaseJsonResource($results->filter(function ($result) { return $result->question; })->map(function ($result) {
      $diff = Carbon::parse($result->end_time)->diff(Carbon::parse($result->start_time))->format('%H:%I:%S');

      return [
        'question' => new QuestionResource($result->question),
        'answers' => new AnswerCollection($result->answers
          ->filter(function ($answer) {
            return $answer->answer;
        })
          ->map(function ($answer) {
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

  function delete (Request $request) {
    $attemptId = $request->attemptId;

    Result::query()->where('attempt_id', $attemptId)->delete();
    ResultAttemptQuestion::query()->where('attempt_id', $attemptId)->delete();
    ResultAttemptQuestionAnswer::query()->where('attempt_id', $attemptId)->delete();
    ResultAttempt::query()->where('id', $attemptId)->delete();

    return response()->json('ok');
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
