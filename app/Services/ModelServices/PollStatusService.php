<?php

namespace App\Services\ModelServices;

use App\Models\PollStatus;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class PollStatusService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\PollStatus";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}