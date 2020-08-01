<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id');
            $table->foreignId('default_question_id')->nullable();
            $table->string('value', 256)->nullable();
            $table->boolean('answer');
            $table->timestamps();

            $table->foreign('test_id')
                ->references('id')
                ->on('tests')
                ->onDelete('cascade');
            $table->foreign('default_question_id')
                ->references('id')
                ->on('default_questions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
