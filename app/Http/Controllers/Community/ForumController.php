<?php

namespace App\Http\Controllers\Community;

use Forums;
use ForumThreads;
use ForumThreadPosts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Forum\CreateSubForumRequest;
use App\Http\Requests\Community\Forum\CreateThreadRequest;
use App\Http\Requests\Community\Forum\ReplyToThreadRequest;

class ForumController extends Controller
{
    public function getOverview($slug = null)
    {
        $forum = Forums::getBySlug($slug);
        if (!$forum)
        {
            if (is_null($slug))
            {
                flash(__("forums.general_forum_not_found"))->error();
                return redirect()->route("home");
            }
            else
            {
                flash(__("forums.subforum_not_found"))->error();
                return redirect()->route("forum");
            }
        }

        return view("pages.community.forum.overview", [
            "forum" => $forum,
        ]);
    }

    public function getCreateSubforum($slug = null)
    {
        $forum = Forums::getBySlug($slug);

        return view("pages.community.forum.create-subforum", [
            "forum" => $forum,
        ]);
    }

    public function postCreateSubforum(CreateSubforumRequest $request, $slug = null)
    {
        $forum = Forums::getBySlug($slug);

        $subforum = Forums::createSubforumFromRequest($forum, $request);

        flash(__("forums.created_subforum"))->success();
        return redirect()->route("forum", $subforum->slug);
    }

    public function getCreateThread($slug = null)
    {
        return view("pages.community.forum.create-thread", [

        ]);
    }

    public function postCreateThread(CreateThreadRequest $request, $slug = null)
    {

    }

    public function getViewThread($slug = null, $threadSlug)
    {
        return view("pages.community.forum.thread", [

        ]);
    }
    
    public function getReplyToThread($slug = null, $threadSlug)
    {
        return view("pages.community.forum.reply-to-thread", [

        ]);
    }

    public function postReplyToThread(ReplyToThreadRequest $request, $slug = null, $threadSlug)
    {

    }
}