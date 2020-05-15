<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TeamRolesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "team-roles";
    }
}