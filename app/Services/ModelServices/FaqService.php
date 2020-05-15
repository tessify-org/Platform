<?php

namespace App\Services\ModelServices;

use Auth;
use App\Models\Faq;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class FaqService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Faq";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}