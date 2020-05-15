<?php

namespace App\Services\ModelServices;

use App\Models\AssignmentType;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Assignments\Types\CreateAssignmentTypeRequest;
use App\Http\Requests\Assignments\Types\UpdateAssignmentTypeRequest;
use App\Http\Requests\Api\Assignments\Types\CreateAssignmentTypeRequest as ApiCreateRequest;
use App\Http\Requests\Api\Assignments\Types\UpdateAssignmentTypeRequest as ApiUpdateRequest;

class AssignmentTypeService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\AssignmentType";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}