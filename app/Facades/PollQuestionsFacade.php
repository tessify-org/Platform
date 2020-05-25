<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PollQuestionsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "poll-questions";
    }
}