<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GroupRolesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "group-roles";
    }
}