<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestsResult extends Model
{
  use HasFactory;

  protected $table = 'tests_results';

  protected $guarded = [];

  public function question () {
    return $this->hasOne(Question::class, 'id', 'question_id');
  }
}