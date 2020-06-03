<?php

namespace App\Services\ModelServices;

use App\Models\ForumThreadPost;
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
        $this->model = "App\Models\ForumThreadPost";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}