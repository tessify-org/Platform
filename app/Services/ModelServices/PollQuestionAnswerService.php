<?php

namespace App\Services\ModelServices;

use App\Models\PollQuestionAnswer;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class PollQuestionAnswerService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\PollQuestionAnswer";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}