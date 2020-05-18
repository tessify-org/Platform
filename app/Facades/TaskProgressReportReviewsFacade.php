<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TaskProgressReportReviewsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "task-progress-report-reviews";
    }
}