<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultAttemptQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_attempt_questions', function (Blueprint $table) {
          $table->id();

          $table->string('attempt_id')->unsigned()->nullable();
          $table->unsignedBigInteger('test_id')->unsigned()->nullable();
          $table->unsignedBigInteger('question_id')->unsigned()->nullable();
          $table->unsignedBigInteger('answer_id')->unsigned()->nullable();
          $table->unsignedBigInteger('user_id')->unsigned()->nullable();

          $table->dateTime('start_time')->nullable();
          $table->dateTime('end_time')->nullable();

          $table->foreign('attempt_id')->references('id')->on('result_attempts')->onDelete('set null');
          $table->foreign('test_id')->references('id')->on('tests')->onDelete('set null');
          $table->foreign('question_id')->references('id')->on('questions')->onDelete('set null');
          $table->foreign('answer_id')->references('id')->on('answers')->onDelete('set null');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('result_attempt_questions');
    }
}
