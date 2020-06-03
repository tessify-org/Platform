<?php

namespace App\Services\ModelServices;

use App\Models\ForumThread;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class ForumThreadService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\ForumThread";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function getAllForForum(Forum $forum)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $thread)
        {
            if ($thread->forum_id == $forum->id)
            {
                $out[] = $thread;
            }
        }

        return $out;
    }
}