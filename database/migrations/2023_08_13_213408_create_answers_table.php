<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->string('answer');
            $table->string('description')->nullable();
            $table->boolean('correct')->default(false);
            $table->unsignedBigInteger('test_id')->unsigned()->nullable();;
            $table->unsignedBigInteger('question_id')->unsigned()->nullable();;
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
        Schema::dropIfExists('answers');
    }
}
