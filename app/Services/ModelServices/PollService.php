<?php

namespace App\Services\ModelServices;

use PollVotes;
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

        $instance->questions = PollQuestions::getAllForPoll($instance);
        $instance->votes = PollVotes::getAllForPoll($instance);

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

    }
}