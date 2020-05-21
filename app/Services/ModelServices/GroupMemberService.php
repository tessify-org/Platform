<?php

namespace App\Services\ModelServices;

use Auth;
use Users;
use GroupRoles;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Community\Groups\Members\KickGroupMemberRequest;

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
        $instance->user = Users::findPreloaded($instance->user_id);
        $instance->role = GroupRoles::find($instance->group_role_id);

        return $instance;
    }

    public function getAllForGroup(Group $group)
    {
        $out = [];

        foreach ($this->getAll() as $member)
        {
            if ($member->group_id == $group->id)
            {
                $out[] = $member;
            }
        }

        return $out;
    }

    public function getAllPreloadedForGroup(Group $group)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $member)
        {
            if ($member->group_id == $group->id)
            {
                $out[] = $member;
            }
        }

        return $out;
    }

    public function isMember(Group $group, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();

        foreach ($this->getAll() as $member)
        {
            if ($member->user_id == $user->id && $member->group_id == $group->id)
            {
                return true;
            }
        }

        return false;
    }

    public function count(Group $group)
    {
        $out = 0;

        foreach ($this->getAll() as $member)
        {
            if ($member->group_id == $group->id)
            {
                $out += 1;
            }
        }

        return $out;
    }

    public function leave(Group $group)
    {
        $user = auth()->user();

        foreach ($this->getAll() as $member)
        {
            if ($member->group_id == $group->id && $member->user_id == $user->id)
            {
                $member->delete();
                return true;
            }
        }

        return false;
    }

    public function processKickRequest(KickGroupMemberRequest $request)
    {
        foreach ($this->getAll() as $member)
        {
            if ($member->id == $request->group_member_id)
            {
                return $member->delete();
            }
        }

        return false;
    }
}