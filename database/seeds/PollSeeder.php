<?php

use App\Models\User;
use App\Models\Poll;
use App\Models\PollQuestion;
use App\Models\PollQuestionAnswer;
use App\Models\PollStatus;
use App\Models\PollVote;
use App\Models\PollVoteAnswer;
use Illuminate\Database\Seeder;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("polls")->delete();
        DB::table("poll_questions")->delete();
        DB::table("poll_question_answers")->delete();
        DB::table("poll_statuses")->delete();
        DB::table("poll_votes")->delete();
        DB::table("poll_vote_answers")->delete();

        //
        // User
        //

        $user = User::find(1);

        //
        // Statuses
        //

        $draft = PollStatus::create([
            "name" => [
                "nl" => "Concept-versie",
                "en" => "Draft-version"
            ],
            "description" => [
                "nl" => "Nog niet gepubliceerd",
                "en" => "Not yet published",
            ],
        ]);
        $open = PollStatus::create([
            "name" => [
                "nl" => "Open",
                "en" => "Open",
            ],
            "description" => [
                "nl" => "Openstaande poll, in afwachting van stemmen.",
                "en" => "Open poll, awaiting votes.",
            ],
        ]);
        $closed = PollStatus::create([
            "name" => [
                "nl" => "Gesloten",
                "en" => "Closed",
            ],
            "description" => [
                "nl" => "Deze poll is inmiddels afgerond.",
                "en" => "This poll has been closed.",
            ],
        ]);

        //
        // Test poll
        //

        $poll = Poll::create([
            "user_id" => $user->id,
            "poll_status_id" => $open->id,
            "title" => "Test poll",
            "description" => [
                "nl" => "Test poll om te proberen",
                "en" => "Test poll to test everything",
            ],
            "published" => true,
            "public" => true,
        ]);

        $poll_question = PollQuestion::create([
            "poll_id" => $poll->id,
            "question" => [
                "en" => "Welke dag is het vandaag?",
                "nl" => "What day is it today?",
            ],
        ]);
        $poll_question_answer = PollQuestionAnswer::create([
            "poll_question_id" => $poll_question->id,
            "value" => [
                "en" => "Monday",
                "nl" => "Maandag",
            ],
        ]);
        $poll_question_answer_two = PollQuestionAnswer::create([
            "poll_question_id" => $poll_question->id,
            "value" => [
                "en" => "Another day",
                "nl" => "Andere dag",
            ],
        ]);

        $poll_question_two = PollQuestion::create([
            "poll_id" => $poll->id,
            "question" => [
                "en" => "Hoeveel katten heb je?",
                "nl" => "How many cats do you have?",
            ]
        ]);
        $poll_question_two_answer = PollQuestionAnswer::create([
            "poll_question_id" => $poll_question_two->id,
            "value" => [
                "en" => "No cats!",
                "nl" => "Geen katten!",
            ],
        ]);
        $poll_question_two_answer_two = PollQuestionAnswer::create([
            "poll_question_id" => $poll_question_two->id,
            "value" => [
                "en" => "1 cat",
                "nl" => "1 kat",
            ]
        ]);
        $poll_question_two_answer_three = PollQuestionAnswer::create([
            "poll_question_id" => $poll_question_two->id,
            "value" => [
                "en" => "2 cats",
                "nl" => "2 katten",
            ],
        ]);
    }
}
