<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TaskProgressReportsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "task-progress-reports";
    }
}