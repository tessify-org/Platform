<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ReputationTransactionsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "reputation-transactions";
    }
}