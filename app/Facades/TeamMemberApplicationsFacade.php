<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TeamMemberApplicationsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "team-member-applications";
    }
}