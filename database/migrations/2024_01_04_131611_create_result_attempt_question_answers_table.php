<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultAttemptQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_attempt_question_answers', function (Blueprint $table) {
          $table->id();


          $table->unsignedBigInteger('result_attempt_question_id')->unsigned()->nullable();
          $table->unsignedBigInteger('answer_id')->unsigned()->nullable();

          $table->foreign('result_attempt_question_id')->references('id')->on('result_attempt_questions')->onDelete('cascade');
          $table->foreign('answer_id')->references('id')->on('answers')->onDelete('set null');

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
        Schema::dropIfExists('result_attempt_question_answers');
    }
}
