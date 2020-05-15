<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CompletedTasksFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "completed-tasks";
    }
}