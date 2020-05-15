<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TaskStatusesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "task-statuses";
    }
}