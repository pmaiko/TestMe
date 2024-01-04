<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
          $table->id();

          $table->string('time')->nullable();;
          $table->decimal('percentage', 5, 2)->nullable();;
          $table->integer('count_questions')->nullable();;
          $table->integer('count_errors')->nullable();;
          $table->integer('count_successes')->nullable();;
          $table->integer('count_misses')->nullable();;

          $table->unsignedBigInteger('attempt_id')->unsigned()->nullable()->unique();
          $table->unsignedBigInteger('test_id')->unsigned()->nullable();
          $table->unsignedBigInteger('user_id')->unsigned()->nullable();

          $table->foreign('attempt_id')->references('id')->on('result_attempts')->onDelete('set null');
          $table->foreign('test_id')->references('id')->on('tests')->onDelete('set null');
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
        Schema::dropIfExists('results');
    }
}
