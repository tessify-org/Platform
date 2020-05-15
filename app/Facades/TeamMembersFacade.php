<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TeamMembersFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "team-members";
    }
}