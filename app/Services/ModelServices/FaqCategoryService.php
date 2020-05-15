<?php

namespace App\Services\ModelServices;

use Auth;
use App\Models\FaqCategory;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class FaqCategoryService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\FaqCategory";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}