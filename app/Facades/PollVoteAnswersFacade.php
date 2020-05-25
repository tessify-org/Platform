<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PollVoteAnswersFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "poll-vote-answers";
    }
}