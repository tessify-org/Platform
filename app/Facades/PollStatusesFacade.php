<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PollStatusesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "poll-statuses";
    }
}