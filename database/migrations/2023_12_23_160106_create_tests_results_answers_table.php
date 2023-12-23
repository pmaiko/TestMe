<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsResultsAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests_results_answers', function (Blueprint $table) {
          $table->id();


          $table->unsignedBigInteger('tests_results_id')->unsigned()->nullable();
          $table->unsignedBigInteger('answer_id')->unsigned()->nullable();

          $table->foreign('tests_results_id')->references('id')->on('tests_results')->onDelete('cascade');
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
        Schema::dropIfExists('tests_results_answers');
    }
}
