<?php

namespace App\Http\Controllers\Community\Groups;

use Tags;
use Polls;
use Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Groups\CreateGroupRequest;
use App\Http\Requests\Community\Groups\UpdateGroupRequest;
use App\Http\Requests\Community\Groups\DeleteGroupRequest;

class GroupController extends Controller
{
    public function getOverview()
    {
        return view("pages.community.groups.overview", [
            "groups" => Groups::getAllPreloaded(),
            "strings" => collect([
                "no_records" => __("groups.overview_no_records"),
            ]),
        ]);
    }
    
    public function getView($slug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        return view("pages.community.groups.view", [
            "group" => $group,
        ]);
    }

    public function getSubscribe($slug)
    {
        $group = Groups::findBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }
        
        auth()->user()->subscribe($group);

        flash(__("groups.subscribed"))->success();
        return redirect()->route("group", $group->slug);
    }

    public function getUnsubscribe($slug)
    {
        $group = Groups::findBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        auth()->user()->unsubscribe($group);

        flash(__("groups.unsubscribed"))->success();
        return redirect()->route("group", $group->slug);
    }

    public function getCreate()
    {
        return view("pages.community.groups.create", [
            "tags" => Tags::getAll(),
            "oldInput" => collect([
                "name" => old("name"),
                "slogan_nl" => old("slogan_nl"),
                "slogan_en" => old("slogan_en"),
                "description_nl" => old("description_nl"),
                "description_en" => old("description_en"),
                "tags" => old("tags"),
            ]),
            "strings" => collect([
                "name" => __("groups.form_name"),
                "slogan" => __("groups.form_slogan"),
                "description" => __("groups.form_description"),
                "tags" => __("groups.form_tags"),
                "header_image" => __("groups.form_header_image"),
                "avatar_image" => __("groups.form_avatar_image"),
                "cancel" => __("groups.create_cancel"),
                "submit" => __("groups.create_submit"),
                "en" => __("general.en"),
                "nl" => __("general.nl"),
            ]),
            "apiEndpoints" => collect([
                "upload"
            ]),
            "defaultImages" => collect([
                "header" => asset("storage/images/groups/headers/default.jpg"),
            ]),
        ]);
    }

    public function postCreate(CreateGroupRequest $request)
    {
        $group = Groups::createFromRequest($request);

        flash(__("groups.created"))->success();
        return redirect()->route("group", $group->slug);
    }

    public function getEdit($slug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $group->header_image_url = asset($group->header_image_url);

        return view("pages.community.groups.edit", [
            "group" => $group,
            "tags" => Tags::getAll(),
            "oldInput" => collect([
                "name" => old("name"),
                "slogan_nl" => old("slogan_nl"),
                "slogan_en" => old("slogan_en"),
                "description_nl" => old("description_nl"),
                "description_en" => old("description_en"),
                "tags" => old("tags"),
            ]),
            "strings" => collect([
                "name" => __("groups.form_name"),
                "slogan" => __("groups.form_slogan"),
                "description" => __("groups.form_description"),
                "tags" => __("groups.form_tags"),
                "header_image" => __("groups.form_header_image"),
                "avatar_image" => __("groups.form_avatar_image"),
                "cancel" => __("groups.update_cancel"),
                "submit" => __("groups.update_submit"),
                "en" => __("general.en"),
                "nl" => __("general.nl"),
            ]),
        ]);
    }

    public function postEdit(UpdateGroupRequest $request, $slug)
    {
        $group = Groups::findBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        $group = Groups::updateFromRequest($group, $request);

        flash(__("groups.updated"))->success();
        return redirect()->route("group", $group->slug);
    }

    public function getDelete($slug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        return view("pages.community.groups.delete", [
            "group" => $group
        ]);
    }

    public function postDelete(DeleteGroupRequest $request, $slug)
    {
        $group = Groups::findBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        Groups::delete($group);

        flash(__("groups.deleted"))->success();
        return redirect()->route("groups");
    }

    public function getPolls($slug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        return view("pages.community.groups.polls.overview", [
            "group" => $group,
            "polls" => Polls::getAllForGroup($group),
            "strings" => collect([
                "no_records" => __("groups.polls_no_records"),
                "voted" => __("groups.polls_voted"),
                "not_voted" => __("groups.polls_not_voted"),
            ]),
        ]);
    }

    public function getForum($slug)
    {
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        return view("pages.community.groups.forum.overview", [
            "group" => $group
        ]);
    }
}