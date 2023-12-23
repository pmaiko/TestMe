<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $answers
 */
class Question extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */
    protected $table = "questions";
    protected $guarded = [];

    public function answers () : HasMany {
        return $this->hasMany(Answer::class, 'question_id');
    }
}
