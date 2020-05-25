<?php

namespace App\Services\ModelServices;

use App\Models\Poll;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class PollService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Poll";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}