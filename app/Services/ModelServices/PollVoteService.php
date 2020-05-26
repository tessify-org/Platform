<?php

namespace App\Services\ModelServices;

use Users;
use App\Models\Poll;
use App\Models\PollVote;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class PollVoteService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\PollVote";
    }
    
    public function preload($instance)
    {
        $instance->user = Users::find($instance->user_id);
    
        return $instance;
    }

    public function getAllForPoll(Poll $poll)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $vote)
        {
            if ($vote->poll_id == $poll->id)
            {
                $out[] = $vote;
            }
        }

        return $out;
    }
}