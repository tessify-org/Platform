<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProjectsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "projects";
    }
}