<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PollVotesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "poll-votes";
    }
}