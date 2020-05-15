<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProjectResourcesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "project-resources";
    }
}