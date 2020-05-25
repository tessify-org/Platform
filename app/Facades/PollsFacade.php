<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PollsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "polls";
    }
}