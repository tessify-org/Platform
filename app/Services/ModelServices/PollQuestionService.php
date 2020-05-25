<?php

namespace App\Services\ModelServices;

use App\Models\PollQuestion;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class PollQuestionService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\PollQuestion";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}