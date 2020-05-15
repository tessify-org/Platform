<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FeedActivitiesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "feed-activities";
    }
}