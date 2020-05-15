<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProjectCategoriesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "project-categories";
    }
}