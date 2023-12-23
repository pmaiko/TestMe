<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests_results', function (Blueprint $table) {
          $table->id();
          $table->string('attempt')->index();

          $table->unsignedBigInteger('user_id')->unsigned()->nullable();
          $table->unsignedBigInteger('test_id')->unsigned()->nullable();
          $table->unsignedBigInteger('question_id')->unsigned()->nullable();

          $table->boolean('has_answer')->default(false);
          $table->dateTime('start_time')->nullable();
          $table->dateTime('end_time')->nullable();

          $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
          $table->foreign('test_id')->references('id')->on('tests')->onDelete('set null');
          $table->foreign('question_id')->references('id')->on('questions')->onDelete('set null');

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
        Schema::dropIfExists('tests_results');
    }
}
