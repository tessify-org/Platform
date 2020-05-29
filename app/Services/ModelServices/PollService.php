<?php

namespace App\Services\ModelServices;

use Users;
use PollVotes;
use PollStatuses;
use PollQuestions;
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

        $instance->user = Users::findPreloaded($instance->user_id);
        $instance->questions = PollQuestions::getAllForPoll($instance);
        $instance->votes = PollVotes::getAllForPoll($instance);

        $instance->is_owner = $this->userOwnsPoll($instance);
        $instance->has_voted = PollVotes::userHasVoted($instance);

        return $instance;
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
        
        foreach (PollVotes::getAllForPoll($poll) as $vote)
        {
            dd($vote->answers);
        }

        $poll->results = $results;
        $poll->save();

        return $poll;
    }
}