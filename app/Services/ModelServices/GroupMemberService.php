<?php

namespace App\Services\ModelServices;

use Auth;
use App\Models\Group;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class GroupMemberService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\GroupMember";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}