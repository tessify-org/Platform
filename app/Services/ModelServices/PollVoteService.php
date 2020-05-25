<?php

namespace App\Services\ModelServices;

use App\Models\PollVote;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class PollVoteService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\PollVote";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}