<?php

namespace App\Services\ModelServices;

use ForumThreads;
use ForumThreadPosts;

use App\Models\Group;
use App\Models\Forum;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Community\Forums\CreateSubforumRequest;
use App\Http\Requests\Community\Forums\UpdateSubforumRequest;
use App\Http\Requests\Community\Forums\DeleteSubforumRequest;

class ForumService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Forum";
    }
    
    public function preload($instance)
    {
        $instance->threads = ForumThreads::getAllForForum($instance);

        return $instance;
    }

    public function findGeneralForum()
    {
        foreach ($this->getAll() as $forum)
        {
            if (is_null($forum->forumable_type) and is_null($forum->forumable_id) and is_null($forum->parent_forum_id))
            {
                return $forum;
            }
        }

        return false;
    }

    public function findPreloadedGeneralForum()
    {
        foreach ($this->getAllPreloaded() as $forum)
        {
            if (is_null($forum->forumable_type) and is_null($forum->forumable_id) and is_null($forum->parent_forum_id))
            {
                return $forum;
            }
        }

        return false;
    }

    public function findBySlug($slug = null)
    {
        if (is_null($slug))
        {
            return $this->findGeneralForum();
        }

        foreach ($this->getAll() as $forum)
        {
            if ($forum->slug == $slug)
            {
                return $forum;
            }
        }

        return false;
    }

    public function findPreloadedBySlug($slug = null)
    {
        if (is_null($slug))
        {
            return $this->findPreloadedGeneralForum();
        }

        foreach ($this->getAllPreloaded() as $forum)
        {
            if ($forum->slug == $slug)
            {
                return $forum;
            }
        }

        return false;
    }

    public function findGroupForum(Group $group, $slug = null)
    {
        foreach ($this->getAllPreloaded() as $forum)
        {
            if (is_null($slug))
            {
                if ($forum->forumable_type == get_class($group) && intval($forum->forumable_id) == $group->id)
                {
                    return $forum;
                }
            }
            else
            {
                // if ($forum->forumable_type == get_class($group) && intval($forum->forumable_id) == $group->id && $forum->slug == $slug)
                if ($forum->slug == $slug)
                {
                    return $forum;
                }
            }
        }

        return false;
    }

    public function createSubforumFromRequest(Forum $forum, CreateSubforumRequest $request)
    {
        return Forum::create([
            "parent_forum_id" => $forum->id,
            "title" => $request->title,
            "description" => $request->description,
        ]);
    }

    public function updateSubforumFromRequest(Forum $forum, UpdateSubforumRequest $request)
    {
        $forum->title = $request->title;
        $forum->description = $request->description;
        $forum->save();

        return $forum;
    }

    public function deleteSubforumFromRequest(Forum $forum)
    {
        $forum->delete();
    }
}