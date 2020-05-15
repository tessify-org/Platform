<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OrganizationsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "organizations";
    }
}