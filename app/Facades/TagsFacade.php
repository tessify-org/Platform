<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TagsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "tags";
    }
}