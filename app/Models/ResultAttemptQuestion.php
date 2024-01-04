<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultAttemptQuestion extends Model
{
  use HasFactory;

  protected $guarded = [];

  public function question () {
    return $this->hasOne(Question::class, 'id', 'question_id');
  }

  public function answer () {
    return $this->hasOne(Answer::class, 'id', 'answer_id');
  }

  public function answers () {
    return $this->hasMany(ResultAttemptQuestionAnswer::class, 'result_attempt_question_id', 'id');
  }
}
