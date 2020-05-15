<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MinistriesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "ministries";
    }
}