<?php

namespace App\Services\ModelServices;

use Auth;
use Tags;
use Uploader;
use GroupRoles;
use GroupMembers;
use GroupMemberApplications;
use App\Models\Group;
use App\Models\GroupRole;
use App\Models\GroupMember;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Community\Groups\CreateGroupRequest;
use App\Http\Requests\Community\Groups\UpdateGroupRequest;
use App\Http\Requests\Community\Groups\DeleteGroupRequest;

class GroupService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Group";
    }
    
    public function preload($instance)
    {
        $instance->view_href = route("group", $instance->slug);

        $instance->header_image_url = asset($instance->header_image_url);
        $instance->avatar_image_url = asset($instance->avatar_image_url);

        $instance->tags = Tags::getAllForGroup($instance);
        $instance->roles = GroupRoles::getAllForGroup($instance);
        $instance->members = GroupMembers::getAllForGroup($instance);
        $instance->applications = GroupMemberApplications::getAllForGroup($instance);

        $instance->is_member = GroupMembers::isMember($instance);
        $instance->is_founder = $instance->founder_id == auth()->user()->id;
        $instance->has_outstanding_application = GroupMemberApplications::hasOutstandingApplication($instance);

        $instance->num_members = GroupMembers::count($instance);
        $instance->num_roles = GroupRoles::count($instance);
        $instance->num_outstanding_applications = GroupMemberApplications::countOutstanding($instance);
        $instance->num_open_polls = 0;

        return $instance;
    }

    public function findBySlug($slug)
    {
        foreach ($this->getAll() as $group)
        {
            if ($group->slug == $slug)
            {
                return $group;
            }
        }

        return false;
    }

    public function findPreloadedBySlug($slug)
    {
        foreach ($this->getAllPreloaded() as $group)
        {
            if ($group->slug == $slug)
            {
                return $group;
            }
        }

        return false;
    }

    public function createFromRequest(CreateGroupRequest $request)
    {
        // Grab the logged in user
        $user = auth()->user();

        // Data to create the group with
        $data = [
            "founder_id" => $user->id,
            "name" => request("name"),
            "slogan" => [
                "nl" => request("slogan_nl"),
                "en" => request("slogan_en"),
            ],
            "description" => [
                "nl" => request("description_nl"),
                "en" => request("description_en"),
            ],
        ];

        // Upload image if one was provided
        if ($request->hasFile("header_image"))
        {
            $image_url = Uploader::upload($request->file("header_image"), "images/groups/headers");
            $data["header_image_url"] = $image_url;
            $data["avatar_image_url"] = $image_url;
        }

        // Create the group
        $group = Group::create($data);

        // Process the received tags
        $group = $this->processGroupTags($group, request("tags"));

        // Create the founder role
        $founder = GroupRole::create([
            "group_id" => $group->id,
            "name" => "Founder",
            "description" => [
                "en" => "",
                "nl" => "",
            ],
        ]);

        // Make the founder a member of the group
        GroupMember::create([
            "user_id" => $user->id,
            "group_id" => $group->id,
            "group_role_id" => $founder->id,
        ]);

        // Return the created group
        return $group;
    }

    public function updateFromRequest(Group $group, UpdateGroupRequest $request)
    {
        // Update the group & save changes
        $group->name = request("name");
        $group->slogan = [
            "en" => request("slogan_en"),
            "nl" => request("slogan_nl"),
        ];
        $group->description = [
            "en" => request("description_en"),
            "nl" => request("description_nl"),
        ];
        if ($request->hasFile("header_image"))
        {
            $image_url = Uploader::upload($request->file("header_image"), "images/groups/headers");
            $group->header_image_url = $image_url;
            $group->avatar_image_url = $image_url;
        }
        $group->save();

        // Process the received tags
        $group = $this->processGroupTags($group, request("tags"));

        // Return the updated group
        return $group;
    }

    private function processGroupTags(Group $group, string $encodedTags)
    {
        // Decode the encoded tag names
        $tags = json_decode($encodedTags);

        // Collect tag id's from the decoded tags
        $tag_ids = [];
        if (is_array($tags) && count($tags))
        {
            foreach ($tags as $tagName)
            {
                $tag = Tags::findOrCreateByName($tagName);
                $tag_ids[] = $tag->id;
            }
        }

        // Sync the new tags
        $group->tags()->sync($tag_ids);

        // Return the updated group
        return $group;
    }

    public function delete(Group $group)
    {
        // Delete all the group's members and roles
        $group->members()->delete();
        $group->roles()->delete();

        // Delete the group
        $group->delete();
    }
}