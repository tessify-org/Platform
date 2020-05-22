<?php

namespace App\Services\ModelServices;

use Auth;
use Users;
use GroupMembers;
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
        $instance->user = Users::findPreloaded($instance->user_id);
        $instance->formatted_date = $instance->created_at->format("d-m-Y H:m:s");

        return $instance;
    }

    public function findByUuid($uuid)
    {
        foreach ($this->getAll() as $application)
        {
            if ($application->uuid == $uuid)
            {
                return $application;
            }
        }

        return false;
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

    public function getAllPreloadedForGroup(Group $group)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $application)
        {
            if ($application->group_id == $group->id)
            {
                $out[] = $application;
            }
        }

        return $out;
    }

    public function getOutstandingForGroup(Group $group)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $application)
        {
            if ($application->group_id == $group->id && !$application->processed)
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

    public function accept(GroupMemberApplication $application)
    {
        $application->processed = true;
        $application->accepted = true;
        $application->save();

        GroupMembers::join($application->group, $application->user);

        return $application;
    }

    public function reject(GroupMemberApplication $application)
    {
        $application->processed = true;
        $application->accepted = false;
        $application->save();

        return $application;
    }
}