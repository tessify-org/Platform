<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AssignmentsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "assignments";
    }
}