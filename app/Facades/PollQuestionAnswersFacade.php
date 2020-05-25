<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PollQuestionAnswersFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "poll-question-answers";
    }
}