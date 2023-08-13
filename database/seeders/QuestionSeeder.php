<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
            'question' => 'Ти молодець?',
            'description' => 'Простий тест',
            'test_id' => 1,
            'created_at' => Date::now()->toDateTimeString()
        ]);
    }
}
