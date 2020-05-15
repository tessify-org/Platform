<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WhitelistedDomainsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "whitelisted-domains";
    }
}