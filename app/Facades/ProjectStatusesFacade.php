<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProjectStatusesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "project-statuses";
    }
}