<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class NotificationsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "notifications";
    }
}