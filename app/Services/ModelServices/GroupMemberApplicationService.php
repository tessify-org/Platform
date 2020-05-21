<?php

namespace App\Services\ModelServices;

use Auth;
use App\Models\Group;
use App\Models\GroupMemberApplication;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class GroupMemberApplicationService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\GroupMemberApplication";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function getAllForGroup(Group $group)
    {
        $out = [];

        foreach ($this->getAll() as $application)
        {
            if ($application->group_id == $group->id)
            {
                $out[] = $application;
            }
        }

        return $out;
    }

    public function hasOutstandingApplication(Group $group, User $user = null)
    {
        foreach ($this->getAll() as $application)
        {
            if ($application->group_id == $group->id && $application->user_id == $user->id && !$application->processed)
            {
                return true;
            }
        }

        return false;
    }

    public function countOutstanding(Group $group)
    {
        $out = 0;

        foreach ($this->getAll() as $application)
        {
            if ($application->group_id == $group->id && !$application->processed)
            {
                $out += 1;
            }
        }

        return $out;
    }
}