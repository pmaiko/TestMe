<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $table = "tests";
    protected $guarded = [];

    public function questions () {
        return $this->hasMany(Question::class, 'test_id')->orderBy('created_at', 'desc');
    }
}
