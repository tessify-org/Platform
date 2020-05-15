<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WorkMethodsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "work-methods";
    }
}