<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('answers')->insert([
            'answer' => 'Да',
            'description' => 'Тому що ти молодець!',
            'correct' => true,
            'test_id' => 1,
            'question_id' => 1,
            'created_at' => Date::now()->toDateTimeString()
        ]);

        DB::table('answers')->insert([
            'answer' => 'Ні',
            'test_id' => 1,
            'question_id' => 1,
            'created_at' => Date::now()->toDateTimeString()
        ]);
    }
}
