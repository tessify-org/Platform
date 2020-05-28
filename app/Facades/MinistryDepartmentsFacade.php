<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MinistryDepartmentsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "ministry-departments";
    }
}