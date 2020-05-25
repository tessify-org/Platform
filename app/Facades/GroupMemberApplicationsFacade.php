<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GroupMemberApplicationsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "group-member-applications";
    }
}