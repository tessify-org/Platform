<?php

namespace App\Http\Controllers\Community\Forums;

use Forums;
use ForumThreads;
use ForumThreadPosts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Forums\CreateSubForumRequest;
use App\Http\Requests\Community\Forums\UpdateSubForumRequest;
use App\Http\Requests\Community\Forums\DeleteSubForumRequest;

class ForumController extends Controller
{
    public function getOverview($slug = null)
    {
        $forum = Forums::findBySlug($slug);
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

    public function getCreateSubforum($slug)
    {
        $forum = Forums::findBySlug($slug);

        return view("pages.community.forum.create-subforum", [
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

    public function postCreateSubforum(CreateSubforumRequest $request, $slug)
    {
        $forum = Forums::findBySlug($slug);

        $subforum = Forums::createSubforumFromRequest($forum, $request);

        flash(__("forums.created_subforum"))->success();
        return redirect()->route("forum", $subforum->slug);
    }

    public function getUpdateSubforum($slug)
    {
        $subforum = Forums::findBySlug($slug);
        if (!$subforum)
        {
            flash(__("forums.subforum_not_found"))->error();
            return redirect()->route("forum");
        }

        if (!$subforum->editable)
        {
            flash(__("forums.subforum_not_editable"))->error();
            return redirect()->route("forum", $slug);
        }

        return view("pages.community.forum.update-subforum", [
            "forum" => $subforum,
            "strings" => collect([
                "title" => __("forums.subforums_form_title"),
                "description" => __("forums.subforums_form_description"),
                "cancel" => __("forums.subforums_create_cancel"),
                "submit" => __("forums.subforums_create_submit"),
            ]),
            "oldInput" => collect([
                "title" => old("title"),
                "description" => old("description"),
            ]),
        ]);
    }

    public function postUpdateSubforum(UpdateSubforumRequest $request, $slug)
    {
        $subforum = Forums::findBySlug($slug);
        if (!$subforum)
        {
            flash(__("forums.subforum_not_found"))->error();
            return redirect()->route("forum");
        }

        if (!$subforum->editable)
        {
            flash(__("forums.subforum_not_editable"))->error();
            return redirect()->route("forum", $slug);
        }

        $subforum = Forums::updateSubforumFromRequest($subforum, $request);
        
        flash(__("forums.updated_subforum"))->success();
        return redirect()->route("forum", $subforum->slug);
    }

    public function getDeleteSubforum($slug)
    {
        $subforum = Forums::findBySlug($slug);
        if (!$subforum)
        {
            flash(__("forums.subforum_not_found"))->error();
            return redirect()->route("forum");
        }

        if (!$subforum->deletable)
        {
            flash(__("forums.subforum_not_deletable"))->error();
            return redirect()->route("forum", $slug);
        }

        return view("pages.community.forum.delete-subforum", [
            "forum" => $subforum,
        ]);
    }

    public function postDeleteSubforum(DeleteSubforumRequest $request, $slug)
    {
        $subforum = Forums::findBySlug($slug);
        if (!$subforum)
        {
            flash(__("forums.subforum_not_found"))->error();
            return redirect()->route("forum");
        }
        
        if (!$subforum->deletable)
        {
            flash(__("forums.subforum_not_deletable"))->error();
            return redirect()->route("forum", $slug);
        }

        $parentForum = $subforum->parentForum;

        Forums::deleteSubforumFromRequest($subforum);

        flash(__("forums.deleted_subforum"))->success();
        return redirect()->route("forum", $parentForum->slug);
    }
}