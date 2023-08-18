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
            $table->timestamp('time');
            $table->decimal('percentage', 5, 2);
            $table->integer('count_questions');
            $table->integer('count_errors');
            $table->integer('count_successes');
            $table->integer('count_misses');
            $table->unsignedBigInteger('user_id')->unsigned()->nullable();
            $table->unsignedBigInteger('test_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('test_id')->references('id')->on('tests')->onDelete('set null');
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
