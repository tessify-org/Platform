<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TaskCategoriesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "task-categories";
    }
}