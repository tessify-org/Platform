<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GroupMembersFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "group-members";
    }
}