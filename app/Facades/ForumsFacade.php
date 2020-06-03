<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ForumsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "forums";
    }
}