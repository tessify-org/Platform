<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MessagesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "messages";
    }
}