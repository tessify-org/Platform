<?php

namespace App\Services\ModelServices;

use PollQuestionAnswers;
use App\Models\Poll;
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
        $instance->answers = PollQuestionAnswers::getAllForQuestion($instance);
        
        return $instance;
    }

    public function getAllForPoll(Poll $poll)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $question)
        {
            if ($question->poll_id == $poll->id)
            {
                $out[] = $question;
            }
        }

        return $out;
    }
}