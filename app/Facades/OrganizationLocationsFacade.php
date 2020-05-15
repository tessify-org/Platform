<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OrganizationLocationsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "organization-locations";
    }
}