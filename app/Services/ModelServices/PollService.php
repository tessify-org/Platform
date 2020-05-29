<?php

namespace App\Services\ModelServices;

use Users;
use PollVotes;
use PollStatuses;
use PollQuestions;
use PollQuestionAnswers;
use App\Models\Poll;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Community\Polls\VotePollRequest;
use App\Http\Requests\Community\Polls\CreatePollRequest;
use App\Http\Requests\Community\Polls\UpdatePollRequest;
use App\Http\Requests\Community\Polls\DeletePollRequest;

class PollService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Poll";
    }
    
    public function preload($instance)
    {
        $instance->view_href = route('poll', $instance->slug);

        $instance->votes = PollVotes::getAllForPoll($instance);
        $instance->user = Users::findPreloaded($instance->user_id);
        $instance->questions = PollQuestions::getAllForPoll($instance);

        $instance->is_owner = $this->userOwnsPoll($instance);
        $instance->has_voted = PollVotes::userHasVoted($instance);

        $instance->results = $this->preloadResults($instance);

        return $instance;
    }

    public function preloadResults(Poll $poll)
    {
        $out = [];

        foreach ($poll->results as $question_id => $result)
        {
            $question = PollQuestions::find($question_id);

            if ($question->open_question)
            {
                $out[] = [
                    "question" => $question,
                    "answers" => $result->answers,
                ];
            }
            else
            {
                $answers = [];   
                foreach ((array) $result->answers as $answer_id => $num_votes)
                {
                    $answer = PollQuestionAnswers::find($answer_id);
                    $answers[] = [
                        "answer" => $answer,
                        "num_votes" => $num_votes,
                        "num_votes_percentage" => floor($poll->num_votes / 100 * $num_votes),
                    ];
                }

                $out[] = [
                    "question" => $question,
                    "answers" => $answers,
                ];
            }
        }

        return $out;
    }

    public function findBySlug($slug)
    {
        foreach ($this->getAll() as $poll)
        {
            if ($poll->slug == $slug)
            {
                return $poll;
            }
        }

        return false;
    }

    public function findPreloadedBySlug($slug)
    {
        foreach ($this->getAllPreloaded() as $poll)
        {
            if ($poll->slug == $slug)
            {
                return $poll;
            }
        }

        return false;
    }

    public function createFromRequest(CreatePollRequest $request)
    {
        
    }

    public function updateFromRequest(Poll $poll, UpdatePollRequest $request)
    {

    }

    public function delete(Poll $poll)
    {
        $poll->delete();
    }

    public function voteFromRequest(Poll $poll, VotePollRequest $request)
    {   
        $answers = json_decode($request->answers);

        $vote = PollVotes::createVoteForPoll($poll, $answers);

        $poll->num_votes += 1;
        $poll->save();

        $this->updateResults($poll);

        return $vote;
    }

    public function close(Poll $poll)
    {
        $status = PollStatuses::getByTranslatedName("Closed", "en");
        $poll->poll_status_id = $status->id;
        $poll->save();
        return $poll;
    }

    public function reopen(Poll $poll)
    {
        $status = PollStatuses::getByTranslatedName("Open", "en");
        $poll->poll_status_id = $status->id;
        $poll->save();
        return $poll;
    }

    public function userOwnsPoll(Poll $poll, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();
        return $poll->user_id == $user->id;
    }

    public function updateResults(Poll $poll)
    {
        $results = [];
        
        // Process all votes
        foreach (PollVotes::getAllForPoll($poll) as $vote)
        {
            // Process all this vote's answers
            foreach ($vote->answers as $answer)
            {
                // Grab the question we're currently processing
                $question = PollQuestions::findPreloaded($answer->question_id);

                // Question has an entry results entry
                if (array_key_exists($question->id, $results))
                {
                    if ($question->open_question)
                    {
                        $results[$question->id]["answers"][] = $answer->answer;
                    }
                    else
                    {
                        if (is_array($answer->answer))
                        {
                            foreach ($answer->answer as $answer_id)
                            {
                                $results[$question->id]["answers"][$answer_id] = $results[$question->id]["answers"][$answer_id] + 1;
                            }
                        }
                        else
                        {
                            $results[$question->id]["answers"][$answer->answer] = $results[$question->id]["answers"][$answer->answer] + 1;
                        }
                    }
                }
                // Question does not have an results array entry yet
                else
                {
                    // Open question
                    if ($question->open_question)
                    {
                        // Collect individual answers
                        $results[$question->id] = [
                            "answers" => [
                                $answer->answer
                            ]
                        ];
                    }
                    // Closed question
                    else
                    {
                        // Generate array with all possible answers and their vote count
                        $answers = [];
                        foreach ($question->answers as $questionAnswer)
                        {
                            $answers[$questionAnswer->id] = 0;
                        }

                        // Populate the array with the vote we're currently processing
                        if (is_array($answer->answer))
                        {
                            foreach ($answer->answer as $answer_id)
                            {
                                $answers[$answer_id] += 1;
                            }
                        }
                        else
                        {
                            $answers[$answer->answer] += 1;
                        }

                        $results[$question->id] = [
                            "answers" => $answers
                        ];
                    }
                }
            }
        }

        $poll->results = $results;
        $poll->save();

        return $poll;
    }
}