<?php

namespace App\Services\ModelServices;

use App\Models\PollVoteAnswer;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class PollVoteAnswerService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\PollVoteAnswer";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}