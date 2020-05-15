<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProjectPhasesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "project-phases";
    }
} 