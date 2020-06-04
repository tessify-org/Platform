<?php

namespace App\Http\Controllers\Community\Forums;

use Forums;
use ForumThreads;
use ForumThreadPosts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Forums\Posts\UpdatePostRequest;
use App\Http\Requests\Community\Forums\Posts\DeletePostRequest;

class ForumThreadPostController extends Controller
{
    public function getUpdate($slug, $threadSlug, $uuid)
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

        $post = ForumThreadPosts::findByUuid($uuid);
        if (!$post)
        {
            flash(__("forums.post_not_found"))->error();
            return redirect()->route("forum.thread", ["slug" => $slug, "threadSlug" => $threadSlug]);
        }

        return view("pages.community.forum.posts.update", [
            "forum" => $forum,
            "thread" => $thread,
            "post" => $post,
            "oldInput" => collect([
                "message" => old("message"),
            ]),
            "strings" => collect([
                "message" => __("forums.posts_update_message"),
                "cancel" => __("forums.posts_update_cancel"),
                "submit" => __("forums.posts_update_submit"),
            ])
        ]);
    }

    public function postUpdate(UpdatePostRequest $request, $slug, $threadSlug, $uuid)
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

        $post = ForumThreadPosts::findByUuid($uuid);
        if (!$post)
        {
            flash(__("forums.post_not_found"))->error();
            return redirect()->route("forum.thread", ["slug" => $slug, "threadSlug" => $threadSlug]);
        }

        ForumThreadPosts::updateFromRequest($post, $request);

        flash(__("forums.updated_post"))->success();
        return redirect()->route("forum.thread", ["slug" => $slug, "threadSlug" => $threadSlug]);
    }

    public function getDelete($slug, $threadSlug, $uuid)
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

        $post = ForumThreadPosts::findByUuid($uuid);
        if (!$post)
        {
            flash(__("forums.post_not_found"))->error();
            return redirect()->route("forum.thread", ["slug" => $slug, "threadSlug" => $threadSlug]);
        }

        return view("pages.community.forum.posts.delete", [
            "forum" => $forum,
            "thread" => $thread,
            "post" => $post,
        ]);
    }

    public function postDelete(DeletePostRequest $request, $slug, $threadSlug, $uuid)
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

        $post = ForumThreadPosts::findByUuid($uuid);
        if (!$post)
        {
            flash(__("forums.post_not_found"))->error();
            return redirect()->route("forum.thread", ["slug" => $slug, "threadSlug" => $threadSlug]);
        }

        ForumThreadPosts::deleteFromRequest($post);

        flash(__("forums.deleted_post"))->success();
        return redirect()->route("forum.thread", ["slug" => $slug, "threadSlug" => $threadSlug]);
    }
}