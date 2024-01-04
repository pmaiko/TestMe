<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_attempts', function (Blueprint $table) {
          $table->id();

          $table->unsignedBigInteger('test_id')->unsigned()->nullable();
          $table->unsignedBigInteger('user_id')->unsigned()->nullable();

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
        Schema::dropIfExists('result_attempts');
    }
}
