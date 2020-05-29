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

        //
        // Test poll -> Question #1 (closed question - single answer)
        //

        $poll_question = PollQuestion::create([
            "poll_id" => $poll->id,
            "open_question" => false,
            "allow_multiple_answers" => false,
            "question" => [
                "nl" => "Welke dag is het vandaag?",
                "en" => "What day is it today?",
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

        //
        // Test poll -> Question #2 (closed question - multiple answers)
        //

        $poll_question_two = PollQuestion::create([
            "poll_id" => $poll->id,
            "open_question" => false,
            "allow_multiple_answers" => true,
            "question" => [
                "nl" => "Welke kleuren heeft je kat?",
                "en" => "Which colors does you cat have?",
            ]
        ]);

        $poll_question_two_answer = PollQuestionAnswer::create([
            "poll_question_id" => $poll_question_two->id,
            "value" => [
                "en" => "Blue",
                "nl" => "Blauw",
            ],
        ]);

        $poll_question_two_answer_two = PollQuestionAnswer::create([
            "poll_question_id" => $poll_question_two->id,
            "value" => [
                "en" => "Black",
                "nl" => "Zwart",
            ]
        ]);

        $poll_question_two_answer_three = PollQuestionAnswer::create([
            "poll_question_id" => $poll_question_two->id,
            "value" => [
                "en" => "White",
                "nl" => "Wit",
            ],
        ]);

        //
        // Test poll -> Question #3 (Open question)
        //

        $poll_question_three = PollQuestion::create([
            "poll_id" => $poll->id, 
            "open_question" => true,
            "allow_multiple_answers" => false,
            "question" => [
                "nl" => "Wat vind je tot nu toe van de app?",
                "en" => "How do you like the application so far?",
            ]
        ]);

    }
}
