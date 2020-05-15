<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ReviewRequestsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "review-requests";
    }
}