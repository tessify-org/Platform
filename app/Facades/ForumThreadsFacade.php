<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ForumThreadsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "forum-threads";
    }
}