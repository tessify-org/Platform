<?php

namespace App\Services\ModelServices;

use Organizations;
use App\Models\MinistryDepartment;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class MinistryDepartmentService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\MinistryDepartment";
    }
    
    public function preload($instance)
    {
        $instance->organizations = Organizations::findAllForMinistryDepartment($ministry);

        return $instance;
    }
}