<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ForumThreadPostsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "forum-thread-posts";
    }
}