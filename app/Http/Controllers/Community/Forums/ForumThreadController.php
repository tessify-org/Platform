<?php

namespace App\Http\Controllers\Community\Forums;

use Forums;
use ForumThreads;
use ForumThreadPosts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Forums\Threads\CreateThreadRequest;
use App\Http\Requests\Community\Forums\Threads\UpdateThreadRequest;
use App\Http\Requests\Community\Forums\Threads\DeleteThreadRequest;
use App\Http\Requests\Community\Forums\Threads\ReplyToThreadRequest;

class ForumThreadController extends Controller
{
    public function getCreate($slug)
    {
        $forum = Forums::findBySlug($slug);
        if (!$forum)
        {
            flash(__("forums.forum_not_found"))->error();
            return redirect()->route("forum");
        }

        return view("pages.community.forum.threads.create", [
            "forum" => $forum,
            "oldInput" => collect([
                "title" => old("title"),
                "description" => old("description"),
                "description_hint" => old("description_hint"),
                "message" => old("message"),
                "sticky" => old("sticky"),
                "closed" => old("closed"),
            ]),
            "strings" => collect([
                "title" => __("forums.threads_form_title"),
                "description" => __("forums.threads_form_description"),
                "description_hint" => __("forums.threads_form_description_hint"),
                "message" => __("forums.threads_form_message"),
                "sticky" => __("forums.threads_form_sticky"),
                "closed" => __("forums.threads_form_closed"),
                "cancel" => __("forums.threads_update_cancel"),
                "submit" => __("forums.threads_update_submit"),
            ]),
        ]);
    }

    public function postCreate(CreateThreadRequest $request, $slug)
    {
        $forum = Forums::findBySlug($slug);
        if (!$forum)
        {
            flash(__("forums.forum_not_found"))->error();
            return redirect()->route("forum");
        }

        $thread = ForumThreads::createFromRequest($forum, $request);

        flash(__("forums.created_thread"))->success();
        return redirect()->route("forum.thread", ["slug" => $forum->slug, "threadSlug" => $thread->slug]);
    }

    public function getView($slug, $threadSlug)
    {
        $forum = Forums::findBySlug($slug);
        if (!$forum)
        {
            flash(__("forums.forum_not_found"))->error();
            return redirect()->route("forum");
        }

        $thread = ForumThreads::findPreloadedBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("forums.thread_not_found"))->error();
            return redirect()->route("forum", $forum->slug);
        }

        return view("pages.community.forum.threads.view", [
            "forum" => $forum,
            "thread" => $thread,
            "postStrings" => collect([
                "on" => __("forums.threads_view_replies_on"),
            ]),
            "replyStrings" => collect([
                "message" => __("forums.threads_view_reply_message"),
                "submit" => __("forums.threads_view_reply_submit"),
            ]),
        ]);
    }

    public function getUpdate($slug, $threadSlug)
    {
        $forum = Forums::findBySlug($slug);
        if (!$forum)
        {
            flash(__("forums.forum_not_found"))->error();
            return redirect()->route("forum");
        }

        $thread = ForumThreads::findBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("forums.thread_not_found"))->error();
            return redirect()->route("forum", $forum->slug);
        }

        return view("pages.community.forum.threads.update", [
            "forum" => $forum,
            "thread" => $thread,
            "oldInput" => collect([
                "title" => old("title"),
                "description" => old("description"),
                "description_hint" => old("description_hint"),
                "message" => old("message"),
                "sticky" => old("sticky"),
                "closed" => old("closed"),
            ]),
            "strings" => collect([
                "title" => __("forums.threads_form_title"),
                "description" => __("forums.threads_form_description"),
                "description_hint" => __("forums.threads_form_description_hint"),
                "message" => __("forums.threads_form_message"),
                "sticky" => __("forums.threads_form_sticky"),
                "closed" => __("forums.threads_form_closed"),
                "cancel" => __("forums.threads_update_cancel"),
                "submit" => __("forums.threads_update_submit"),
            ]),
        ]);
    }

    public function postUpdate(UpdateThreadRequest $request, $slug, $threadSlug)
    {
        $forum = Forums::findBySlug($slug);
        if (!$forum)
        {
            flash(__("forums.forum_not_found"))->error();
            return redirect()->route("forum");
        }

        $thread = ForumThreads::findBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("forums.thread_not_found"))->error();
            return redirect()->route("forum", $forum->slug);
        }

        ForumThreads::updateFromRequest($thread, $request);

        flash(__("forums.updated_thread"))->success();
        return redirect()->route("forum.thread", ["slug" => $forum->slug, "threadSlug" => $thread->slug]);
    }

    public function getDelete($slug, $threadSlug)
    {
        $forum = Forums::findBySlug($slug);
        if (!$forum)
        {
            flash(__("forums.forum_not_found"))->error();
            return redirect()->route("forum");
        }

        $thread = ForumThreads::findBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("forums.thread_not_found"))->error();
            return redirect()->route("forum", $forum->slug);
        }

        return view("pages.community.forum.threads.delete", [
            "forum" => $forum,
            "thread" => $thread,
        ]);
    }

    public function postDelete($slug, $threadSlug)
    {
        $forum = Forums::findBySlug($slug);
        if (!$forum)
        {
            flash(__("forums.forum_not_found"))->error();
            return redirect()->route("forum");
        }

        $thread = ForumThreads::findBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("forums.thread_not_found"))->error();
            return redirect()->route("forum", $forum->slug);
        }
        
        ForumThreads::deleteFromRequest($thread);

        flash(__("forums.deleted_thread"))->success();
        return redirect()->route("forum", $forum->slug);
    }

    public function postReplyToThread(ReplyToThreadRequest $request, $slug, $threadSlug)
    {
        $forum = Forums::findBySlug($slug);
        if (!$forum)
        {
            flash(__("forums.forum_not_found"))->error();
            return redirect()->route("forum");
        }

        $thread = ForumThreads::findBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("forums.thread_not_found"))->error();
            return redirect()->route("forum", $forum->slug);
        }
        
        ForumThreadPosts::createFromRequest($thread, $request);

        flash(__("forums.posted_reply"))->success();
        return redirect()->route("forum.thread", ["slug" => $slug, "threadSlug" => $threadSlug]);
    }
}