<?php

namespace App\Http\Controllers\Community\Groups;

use Groups;
use Forums;
use ForumThreads;
use ForumThreadPosts;
use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Forums\CreateSubforumRequest;
use App\Http\Requests\Community\Forums\UpdateSubforumRequest;
use App\Http\Requests\Community\Forums\DeleteSubforumRequest;
use App\Http\Requests\Community\Forums\Posts\UpdatePostRequest;
use App\Http\Requests\Community\Forums\Posts\DeletePostRequest;
use App\Http\Requests\Community\Forums\Threads\CreateThreadRequest;
use App\Http\Requests\Community\Forums\Threads\UpdateThreadRequest;
use App\Http\Requests\Community\Forums\Threads\DeleteThreadRequest;
use App\Http\Requests\Community\Forums\Threads\ReplyToThreadRequest;

class GroupForumController extends Controller
{
    public function getForum($slug, $forumSlug = null)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.group_forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        return view("pages.community.groups.forum.overview", [
            "group" => $group,
            "forum" => $forum,
        ]);
    }

    public function getCreateSubforum($slug, $forumSlug = null)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.group_forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        return view("pages.community.groups.forum.subforums.create", [
            "group" => $group,
            "forum" => $forum,
            "strings" => collect([
                "title" => __("forums.subforums_form_title"),
                "description" => __("forums.subforums_form_description"),
                "cancel" => __("forums.subforums_create_cancel"),
                "submit" => __("forums.subforums_create_submit"),
            ]),
            "oldInput" => collect([
                "title" => old("title"),
                "description" => old("description"),
            ])
        ]);
    }

    public function postCreateSubforum(CreateSubforumRequest $request, $slug, $forumSlug = null)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $subforum = Forums::createSubforumFromRequest($forum, $request);

        flash(__("groups.forum_created_subforum"))->success();
        return redirect()->route("group.forum", ["slug" => $group->slug, "forumSlug" => $subforum->slug]);
    }
    
    public function getUpdateSubforum($slug, $forumSlug = null)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        return view("pages.community.groups.forum.subforums.update", [
            "group" => $group,
            "forum" => $forum,
            "strings" => collect([
                "title" => __("forums.subforums_form_title"),
                "description" => __("forums.subforums_form_description"),
                "cancel" => __("forums.subforums_create_cancel"),
                "submit" => __("forums.subforums_create_submit"),
            ]),
            "oldInput" => collect([
                "title" => old("title"),
                "description" => old("description"),
            ])
        ]);
    }

    public function postUpdateSubforum(UpdateSubforumRequest $request, $slug, $forumSlug = null)
    {
        $group = Groups::findBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findBySlug(    $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $forum = Forums::updateSubforumFromRequest($forum, $request);

        flash(__("groups.forum_updated_subforum"))->success();
        return redirect()->route("group.forum", ["slug" => $slug, "forumSlug" => $forum->slug]);
    }

    public function getDeleteSubforum($slug, $forumSlug = null)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        return view("pages.community.groups.forum.subforums.delete", [
            "group" => $group,
            "forum" => $forum,
        ]);
    }

    public function postDeleteSubforum(DeleteSubforumRequest $request, $slug, $forumSlug = null)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $parentForum = $forum->parentForum;

        Forums::deleteSubforumFromRequest($forum);

        flash(__("groups.forum_deleted_subforum"))->success();
        return redirect()->route("group.forum", ["slug" => $group->slug, "forumSlug" => $parentForum->slug]);
    }

    public function getThread($slug, $forumSlug, $threadSlug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $thread = ForumThreads::findPreloadedBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("groups.forum_thread_not_found"))->error();
            return redirect()->route("group.forum", ["slug" =>$ $slug, "forumSlug" => $forumSlug]);
        }

        return view("pages.community.groups.forum.threads.view", [
            "group" => $group,
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

    public function getCreateThread($slug, $forumSlug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        return view("pages.community.groups.forum.threads.create", [
            "group" => $group,
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

    public function postCreateThread(CreateThreadRequest $request, $slug, $forumSlug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $thread = ForumThreads::createFromRequest($forum, $request);

        flash(__("groups.forum_thread_created"))->success();
        return redirect()->route("group.forum.thread", ["slug" => $group->slug, "forumSlug" => $forum->slug, "threadSlug" => $thread->slug]);
    }

    public function getUpdateThread($slug, $forumSlug, $threadSlug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $thread = ForumThreads::findPreloadedBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("groups.forum_thread_not_found"))->error();
            return redirect()->route("group.forum", ["slug" =>$ $slug, "forumSlug" => $forumSlug]);
        }
        
        return view("pages.community.groups.forum.threads.update", [
            "group" => $group,
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

    public function postUpdateThread(UpdateThreadRequest $request, $slug, $forumSlug, $threadSlug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $thread = ForumThreads::findPreloadedBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("groups.forum_thread_not_found"))->error();
            return redirect()->route("group.forum", ["slug" =>$ $slug, "forumSlug" => $forumSlug]);
        }

        //

        flash(__("groups.forum_updated_thread"))->success();
        return redirect()->route("group.forum.thread", ["slug" => $slug, "forumSlug" => $forumSlug, "threadSlug" => $thread->slug]);
    }

    public function getDeleteThread($slug, $forumSlug, $threadSlug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $thread = ForumThreads::findPreloadedBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("groups.forum_thread_not_found"))->error();
            return redirect()->route("group.forum", ["slug" =>$ $slug, "forumSlug" => $forumSlug]);
        }

        return view("pages.community.groups.forum.threads.delete", [
            "group" => $group,
            "forum" => $forum,
            "thread" => $thread,
        ]);
    }

    public function postDeleteThread(DeleteThreadRequest $request, $slug, $forumSlug, $threadSlug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $thread = ForumThreads::findPreloadedBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("groups.forum_thread_not_found"))->error();
            return redirect()->route("group.forum", ["slug" =>$ $slug, "forumSlug" => $forumSlug]);
        }

        //

        flash(__("groups.forum_deleted_thread"))->success();
        return redirect()->route("group.forum", ["slug" => $slug, "forumSlug" => $forumSlug]);
    }

    public function postReplyToThread(ReplyToThreadRequest $request, $slug, $forumSlug, $threadSlug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $thread = ForumThreads::findPreloadedBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("groups.forum_thread_not_found"))->error();
            return redirect()->route("group.forum", ["slug" =>$ $slug, "forumSlug" => $forumSlug]);
        }

        ForumThreadPosts::createFromRequest($thread, $request);
        
        flash(__("groups.forum_replied_to_thread"))->success();
        return redirect()->route("group.forum.thread", ["slug" => $slug, "forumSlug" => $forumSlug, "threadSlug" => $threadSlug]);
    }

    public function getUpdatePost($slug, $forumSlug, $threadSlug, $uuid)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $thread = ForumThreads::findPreloadedBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("groups.forum_thread_not_found"))->error();
            return redirect()->route("group.forum", ["slug" =>$ $slug, "forumSlug" => $forumSlug]);
        }

        $post = ForumThreadPosts::findByUuid($uuid);
        if (!$post)
        {
            flash(__("groups.forum_post_not_found"))->success();
            return redirect()->route("group.forum.thread", ["slug" => $slug, "forumSlug" => $forumSlug, "threadSlug" => $threadSlug]);
        }

        return view("pages.community.groups.forum.posts.update", [
            "group" => $group,
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

    public function postUpdatePost(UpdatePostRequest $request, $slug, $forumSlug, $threadSlug, $uuid)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $thread = ForumThreads::findPreloadedBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("groups.forum_thread_not_found"))->error();
            return redirect()->route("group.forum", ["slug" =>$ $slug, "forumSlug" => $forumSlug]);
        }

        $post = ForumThreadPosts::findByUuid($uuid);
        if (!$post)
        {
            flash(__("groups.forum_post_not_found"))->success();
            return redirect()->route("group.forum.thread", ["slug" => $slug, "forumSlug" => $forumSlug, "threadSlug" => $threadSlug]);
        }
        
        $post = ForumThreadPosts::updateFromRequest($post, $request);

        flash(__("groups.forum_updated_post"))->success();
        return redirect()->route("group.forum.thread", ["slug" => $slug, "forumSlug" => $forumSlug, "threadSlug" => $threadSlug]);
    }

    public function getDeletePost($slug, $forumSlug, $threadSlug, $uuid)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $thread = ForumThreads::findPreloadedBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("groups.forum_thread_not_found"))->error();
            return redirect()->route("group.forum", ["slug" =>$ $slug, "forumSlug" => $forumSlug]);
        }

        $post = ForumThreadPosts::findByUuid($uuid);
        if (!$post)
        {
            flash(__("groups.forum_post_not_found"))->success();
            return redirect()->route("group.forum.thread", ["slug" => $slug, "forumSlug" => $forumSlug, "threadSlug" => $threadSlug]);
        }
        
        return view("pages.community.groups.forum.posts.delete", [
            "group" => $group,
            "forum" => $forum,
            "thread" => $thread,
            "post" => $post,
        ]);
    }

    public function postDeletePost(DeletePostRequest $request, $slug, $forumSlug, $threadSlug, $uuid)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $forum = Forums::findGroupForum($group, $forumSlug);
        if (!$forum)
        {
            flash(__("groups.forum_not_found"))->error();
            return redirect()->route("group", $group->slug);
        }

        $thread = ForumThreads::findPreloadedBySlug($threadSlug);
        if (!$thread)
        {
            flash(__("groups.forum_thread_not_found"))->error();
            return redirect()->route("group.forum", ["slug" =>$ $slug, "forumSlug" => $forumSlug]);
        }

        $post = ForumThreadPosts::findByUuid($uuid);
        if (!$post)
        {
            flash(__("groups.forum_post_not_found"))->success();
            return redirect()->route("group.forum.thread", ["slug" => $slug, "forumSlug" => $forumSlug, "threadSlug" => $threadSlug]);
        }
        
        ForumThreadPosts::deleteFromRequest($post);

        flash(__("groups.forum_deleted_post"))->success();
        return redirect()->route("group.forum.thread", ["slug" => $slug, "forumSlug" => $forumSlug, "threadSlug" => $threadSlug]);
    }
}