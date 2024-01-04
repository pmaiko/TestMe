<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultAttemptQuestionAnswer extends Model
{
  use HasFactory;

  protected $guarded = [];

  public function answer () {
    return $this->hasOne(Answer::class, 'id', 'answer_id');
  }
}
