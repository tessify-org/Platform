<?php

namespace App\Services\ModelServices;

use Users;
use ForumThreads;
use ForumThreadPosts;
use App\Models\Forum;
use App\Models\ForumThread;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Community\Forums\Threads\CreateThreadRequest;
use App\Http\Requests\Community\Forums\Threads\UpdateThreadRequest;
use App\Http\Requests\Community\Forums\Threads\DeleteThreadRequest;

class ForumThreadService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\ForumThread";
    }
    
    public function preload($instance)
    {
        $instance->user = Users::findPreloaded($instance->user_id);
        $instance->posts = collect(ForumThreadPosts::getAllForThread($instance));

        return $instance;
    }

    public function findBySlug($slug)
    {
        foreach ($this->getAll() as $thread)
        {
            if ($thread->slug == $slug)
            {
                return $thread;
            }
        }

        return false;
    }

    public function findPreloadedBySlug($slug)
    {
        foreach ($this->getAllPreloaded() as $thread)
        {
            if ($thread->slug == $slug)
            {
                return $thread;
            }
        }

        return false;
    }

    public function getAllForForum(Forum $forum)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $thread)
        {
            if ($thread->forum_id == $forum->id)
            {
                $out[] = $thread;
            }
        }

        return $out;
    }

    public function createFromRequest(Forum $forum, CreateThreadRequest $request)
    {
        return ForumThread::create([
            "forum_id" => $forum->id,
            "user_id" => auth()->user()->id,
            "title" => $request->title,
            "description" => $request->description,
            "message" => $request->message,
            "sticky" => $request->sticky == "true" ? true : false,
            "closed" => $request->closed == "true" ? true : false,
        ]);
    }

    public function updateFromRequest(ForumThread $thread, UpdateThreadRequest $request)
    {
        $thread->title = $request->title;
        $thread->description = $request->description;
        $thread->message = $request->message;
        $thread->sticky = $request->sticky == "true" ? true : false;
        $thread->closed = $request->closed == "true" ? true : false;
        $thread->save();

        return $thread;
    }

    public function deleteFromRequest(ForumThread $thread)
    {
        $thread->delete();    
    }
}