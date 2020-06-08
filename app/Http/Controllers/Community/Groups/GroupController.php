<?php

namespace App\Http\Controllers\Community\Groups;

use Tags;
use Users;
use Polls;
use Groups;
use Messages;
use GroupMembers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Groups\CreateGroupRequest;
use App\Http\Requests\Community\Groups\UpdateGroupRequest;
use App\Http\Requests\Community\Groups\DeleteGroupRequest;

class GroupController extends Controller
{
    public function getOverview()
    {
        return view("pages.community.groups.overview", [
            "groups" => Groups::getAllForOverview(),
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
            "users" => Users::getAllPreloaded(),
            "inviteButtonStrings" => collect([
                "button" => __("groups.view_invite_friend"),
                "dialog_title" => __("groups.view_invite_friend_dialog_title"),
                "dialog_text" => __("groups.view_invite_friend_dialog_text"),
                "dialog_form_user" => __("groups.view_invite_friend_dialog_form_user"),
                "dialog_cancel" => __("groups.view_invite_friend_dialog_cancel"),
                "dialog_submit" => __("groups.view_invite_friend_dialog_submit")
            ]),
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
                "hidden" => old("hidden"),
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
                "hidden" => __("groups.form_hidden"),
                "hidden_hint" => __("groups.form_hidden_hint"),
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
                "hidden" => old("hidden"),
            ]),
            "strings" => collect([
                "name" => __("groups.form_name"),
                "slogan" => __("groups.form_slogan"),
                "description" => __("groups.form_description"),
                "tags" => __("groups.form_tags"),
                "header_image" => __("groups.form_header_image"),
                "avatar_image" => __("groups.form_avatar_image"),
                "hidden" => __("groups.form_hidden"),
                "hidden_hint" => __("groups.form_hidden_hint"),
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

    public function getInvite($slug, $userSlug = null)
    {
        // Find preloaded by slug
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }
        
        // Make sure we received a target user
        if (is_null($userSlug))
        {
            flash(__("messages.invitation_failed"))->error();
            return redirect()->route("group", $group->slug);
        }

        // Grab the target user
        $user = Users::findBySlug($userSlug);
        if (!$user)
        {
            flash(__("messages.invitation_failed"))->error();
            return redirect()->route("group", $group->slug);
        }

        // Send invitation message
        Messages::sendInviteToGroupMessage($user, $group);

        // Flash message and redirect back to view task page
        flash(__("messages.invitation_sent"))->success();
        return redirect()->route("group", $group->slug);
    }

    public function getAcceptInvite($slug, $messageUuid)
    {
        // Find preloaded by slug
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        // Find the message by it's uuid
        $message = Messages::findByUuid($messageUuid);
        if (!$message)
        {
            flash(__("messages.message_not_found"))->error();
            return redirect()->route("messages.inbox");
        }

        // Accept the invitation
        Messages::acceptInvitation($message);

        // Join the group
        GroupMembers::join($group);

        // Flash message and redirect to the group page
        flash(__("groups.invite_accepted"))->success();
        return redirect()->route("group", $group->slug);
    }

    public function getRejectInvite($slug, $messageUuid)
    {
        // Find preloaded by slug
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        // Find the message by it's uuid
        $message = Messages::findByUuid($messageUuid);
        if (!$message)
        {
            flash(__("messages.message_not_found"))->error();
            return redirect()->route("messages.inbox");
        }

        // Reject the invitation
        Messages::rejectInvitation($message);

        // Flash message & redirect back to the message
        flash(__("groups.invite_accepted"))->success();
        return redirect()->route("messages.read", $messageUuid);
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