<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollVoteAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_vote_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('poll_vote_id');
            $table->unsignedInteger('poll_question_id');
            $table->unsignedInteger('poll_question_answer_id');
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
        Schema::dropIfExists('poll_vote_answers');
    }
}
