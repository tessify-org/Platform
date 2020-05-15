<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ViewEmailRequestsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "view-email-requests";
    }
}