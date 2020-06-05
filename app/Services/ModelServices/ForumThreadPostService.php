<?php

namespace App\Services\ModelServices;

use Users;
use App\Models\ForumThread;
use App\Models\ForumThreadPost;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Community\Forums\Threads\ReplyToThreadRequest;
use App\Http\Requests\Community\Forums\Posts\UpdatePostRequest;

class ForumThreadPostService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\ForumThreadPost";
    }
    
    public function preload($instance)
    {
        $instance->user = Users::findPreloaded($instance->user_id);
        $instance->formatted_created_at = $instance->created_at->format("d-m-Y H:i:s");

        $forum = $instance->forumThread->forum;

        if ($forum->forumable_type == "App\\Models\\Group")
        {
            $instance->update_href = route('group.forum.update-post', ['slug' => $forum->forumable->slug, 'forumSlug' => $forum->slug, 'threadSlug' => $instance->forumThread->slug, 'uuid' => $instance->uuid]);
            $instance->delete_href = route('group.forum.delete-post', ['slug' => $forum->forumable->slug, 'forumSlug' => $forum->slug, 'threadSlug' => $instance->forumThread->slug, 'uuid' => $instance->uuid]);
        }
        else
        {
            $instance->update_href = route('forum.thread.post.update', ['slug' => $instance->forumThread->forum->slug, 'threadSlug' => $instance->forumThread->slug, 'uuid' => $instance->uuid]);
            $instance->delete_href = route('forum.thread.post.delete', ['slug' => $instance->forumThread->forum->slug, 'threadSlug' => $instance->forumThread->slug, 'uuid' => $instance->uuid]);
        }

        return $instance;
    }

    public function getAllForThread(ForumThread $thread)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $post)
        {
            if ($post->forum_thread_id == $thread->id)
            {
                $out[] = $post;
            }
        }

        return $out;
    }

    public function findByUuid($uuid)
    {
        foreach ($this->getAll() as $post)
        {
            if ($post->uuid == $uuid)
            {
                return $post;
            }
        }

        return false;
    }

    public function findPreloadedByUuid($uuid)
    {
        foreach ($this->getAllPreloaded() as $post)
        {
            if ($post->uuid == $uuid)
            {
                return $post;
            }
        }

        return false;
    }

    public function createFromRequest(ForumThread $thread, ReplyToThreadRequest $request)
    {
        return ForumThreadPost::create([
            "forum_thread_id" => $thread->id,
            "user_id" => auth()->user()->id,
            "message" => $request->message,
        ]);
    }

    public function updateFromRequest(ForumThreadPost $post, UpdatePostRequest $request)
    {
        $post->message = $request->message;
        $post->save();

        return $post;
    }

    public function deleteFromRequest(ForumThreadPost $post)
    {
        $post->delete();
    }
}