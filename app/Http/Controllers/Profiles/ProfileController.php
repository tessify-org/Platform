<?php

namespace App\Http\Controllers\Profiles;

use Auth;
use Tags;
use Users;
use Tasks;
use Skills;
use Comments;
use Projects;
use Messages;
use Reputation;
use Assignments;
use Organizations;
use AssignmentTypes;
use ViewEmailRequests;
use OrganizationLocations;
use OrganizationDepartments;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Events\Users\UserFollowsUser;
use App\Events\Users\UserUnfollowsUser;
use App\Http\Requests\Profiles\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function getProfile($slug = null)
    {
        // Grab the user this profile belongs to
        $user = is_null($slug) ? Auth::user() : User::where("slug", $slug)->first();
        if (!$user)
        {
            flash(__('profiles.user_not_found'))->error();
            return redirect()->route("memberlist");
        }

        // Determine some things
        $is_mine = $user->id == auth()->user()->id;
        $has_sent_view_email_request = $is_mine ? false : ViewEmailRequests::hasSentRequest($user);
        $has_accepted_view_email_request = $is_mine ? false : ViewEmailRequests::canViewEmail($user);
        $can_view_email = $user->publicly_display_email == true || $has_accepted_view_email_request == true;
        
        // Render the profile page
        return view("pages.profiles.profile", [
            "user" => $user,
            "is_mine" => $is_mine,
            "has_sent_view_email_request" => $has_sent_view_email_request,
            "can_view_email" => $can_view_email,
            "followers" => Users::getFollowers($user),
            "following" => Users::getFollowing($user),
            "assignments" => Assignments::findAllPreloadedForUser($user),
            "projects" => Projects::getAllOngoingForUser($user),
            "tasks" => Tasks::getAllOngoingForUser($user),
            "transactions" => Reputation::getTransactionsForUser($user),
            "comments" => Comments::getAllPreloadedForUser($user),
            "assignmentListStrings" => collect([
                "current_assignment" => __("profiles.profile_assignments_current_assignment"),
                "previous_assignments" => __("profiles.profile_assignments_previous_assignments"),
                "no_assignments" => __("profiles.profile_assignments_no_assignments"),
                "assignment" => __("profiles.profile_assignments_assignment"),
                "ministry" => __("profiles.profile_assignments_ministry"),
                "organization" => __("profiles.profile_assignments_organization"),
                "department" => __("profiles.profile_assignments_department"),
                "employment_type" => __("profiles.profile_assignments_employment_type"),
                "function" => __("profiles.profile_assignments_function"),
                "duration" => __("profiles.profile_assignments_duration"),
                "description" => __("profiles.profile_assignments_description"),
            ])
        ]);
    }

    public function getUpdateProfile()
    {
        // Render the update profile page
        return view("pages.profiles.update-profile", [
            "user" => Users::current(),
            "skills" => Skills::getAll(),
            "assignmentTypes" => AssignmentTypes::getAll(),
            "organizations" => Organizations::getAll(),
            "departments" => OrganizationDepartments::getAll(),
            "organizationLocations" => OrganizationLocations::getAll(),
            "tags" => Tags::getAll(),
            "oldInput" => collect([
                "first_name" => old("first_name"),
                "last_name" => old("last_name"),
                "headline" => old("headline"),
                "interests" => old("interests"),
                "email" => old("email"),
                "publicly_display_email" => old("publicly_display_email"),
                "phone" => old("phone"),
                "current_assignment_id" => old("current_assignment_id"),
                "skills" => old("skills"),
            ]),
            "apiEndpoints" => collect([
                "create_assignment" => route("api.assignments.create.post"),
                "update_assignment" => route("api.assignments.update.post"),
                "delete_assignment" => route("api.assignments.delete.post"),
                "upload_avatar" => route("api.profile.upload-avatar.post"),
                "upload_header" => route("api.profile.upload-header-image.post"),
            ]),
            "strings" => collect([
                "first_name" => __("profiles.update_profile_first_name"),
                "last_name" => __("profiles.update_profile_last_name"),
                "headline" => __("profiles.update_profile_headline"),
                "email" => __("profiles.update_profile_email"),
                "publicly_display_email" => __("profiles.update_profile_publicly_display_email"),
                "phone" => __("profiles.update_profile_phone"),
                "avatar" => __("profiles.update_profile_avatar"),
                "header_bg" => __("profiles.update_profile_header_bg"),
                "assignments" => __("profiles.update_profile_assignments"),
                "about" => __("profiles.update_profile_about"),
                "about_hint" => __("profiles.update_profile_about_hint"),
                "interests" => __("profiles.update_profile_interests"),
                "back" => __("profiles.update_profile_go_back"),
                "save" => __("profiles.update_profile_save_changes"),
                "nl" => __("general.nl"),
                "en" => __("general.en"),
                "assignments" => [
                    "label" => __("profiles.update_profile_assignments_label"),
                    "no_records" => __("profiles.update_profile_assignments_no_records"),
                    "add_button" => __("profiles.update_profile_assignments_add_button"),
                    "form_title" => __("profiles.update_profile_assignments_form_title"),
                    "form_description" => __("profiles.update_profile_assignments_form_description"),
                    "form_type" => __("profiles.update_profile_assignments_form_type"),
                    "form_organization" => __("profiles.update_profile_assignments_form_organization"),
                    "form_department" => __("profiles.update_profile_assignments_form_department"),
                    "form_location" => __("profiles.update_profile_assignments_form_location"),
                    "form_current_function" => __("profiles.update_profile_assignments_form_current_function"),
                    "form_start_date" => __("profiles.update_profile_assignments_form_start_date"),
                    "form_present" => __("profiles.update_profile_assignments_form_present"),
                    "form_end_date" => __("profiles.update_profile_assignments_form_end_date"),
                    "form_until_present" => __("profiles.update_profile_assignments_form_until_present"),
                    "create_dialog_title" => __("profiles.update_profile_assignments_create_dialog_title"),
                    "create_dialog_cancel" => __("profiles.update_profile_assignments_create_dialog_cancel"),
                    "create_dialog_submit" => __("profiles.update_profile_assignments_create_dialog_submit"),
                    "view_dialog_title" => __("profiles.update_profile_assignments_view_dialog_title"),
                    "view_dialog_ministry" => __("profiles.update_profile_assignments_view_dialog_ministry"),
                    "view_dialog_organization" => __("profiles.update_profile_assignments_view_dialog_organization"),
                    "view_dialog_department" => __("profiles.update_profile_assignments_view_dialog_department"),
                    "view_dialog_location" => __("profiles.update_profile_assignments_view_dialog_location"),
                    "view_dialog_assignment_type" => __("profiles.update_profile_assignments_view_dialog_assignment_type"),
                    "view_dialog_function" => __("profiles.update_profile_assignments_view_dialog_function"),
                    "view_dialog_duration" => __("profiles.update_profile_assignments_view_dialog_duration"),
                    "view_dialog_description" => __("profiles.update_profile_assignments_view_dialog_description"),
                    "view_dialog_edit" => __("profiles.update_profile_assignments_view_dialog_edit"),
                    "view_dialog_delete" => __("profiles.update_profile_assignments_view_dialog_delete"),
                    "update_dialog_title" => __("profiles.update_profile_assignments_update_dialog_title"),
                    "update_dialog_cancel" => __("profiles.update_profile_assignments_update_dialog_cancel"),
                    "update_dialog_submit" => __("profiles.update_profile_assignments_update_dialog_submit"),
                    "delete_dialog_title" => __("profiles.update_profile_assignments_delete_dialog_title"),
                    "delete_dialog_text" => __("profiles.update_profile_assignments_delete_dialog_text"),
                    "delete_dialog_cancel" => __("profiles.update_profile_assignments_delete_dialog_cancel"),
                    "delete_dialog_submit" => __("profiles.update_profile_assignments_delete_dialog_submit"),
                    "no_organizations" => __("profiles.update_profile_assignments_no_organizations"),
                ],
                "skills" => collect([
                    "label" => __("profiles.update_profile_skills_label"),
                    "no_records" => __("profiles.update_profile_skills_no_records"),
                    "add_button" => __("profiles.update_profile_skills_add_button"),
                    "form_skill" => __("profiles.update_profile_skills_form_skill"),
                    "form_mastery" => __("profiles.update_profile_skills_form_mastery"),
                    "form_description" => __("profiles.update_profile_skills_form_description"),
                    "form_description_hint" => __("profiles.update_profile_skills_form_description_hint"),
                    "view_dialog_title" => __("profiles.update_profile_skills_view_dialog_title"),
                    "view_dialog_skill" => __("profiles.update_profile_skills_view_dialog_skill"),
                    "view_dialog_mastery" => __("profiles.update_profile_skills_view_dialog_mastery"),
                    "view_dialog_description" => __("profiles.update_profile_skills_view_dialog_description"),
                    "view_dialog_edit" => __("profiles.update_profile_skills_view_dialog_edit"),
                    "view_dialog_delete" => __("profiles.update_profile_skills_view_dialog_delete"),
                    "create_dialog_title" => __("profiles.update_profile_skills_create_dialog_title"),
                    "create_dialog_cancel" => __("profiles.update_profile_skills_create_dialog_cancel"),
                    "create_dialog_submit" => __("profiles.update_profile_skills_create_dialog_submit"),
                    "update_dialog_title" => __("profiles.update_profile_skills_update_dialog_title"),
                    "update_dialog_cancel" => __("profiles.update_profile_skills_update_dialog_cancel"),
                    "update_dialog_submit" => __("profiles.update_profile_skills_update_dialog_submit"),
                    "delete_dialog_title" => __("profiles.update_profile_skills_delete_dialog_title"),
                    "delete_dialog_text" => __("profiles.update_profile_skills_delete_dialog_text"),
                    "delete_dialog_cancel" => __("profiles.update_profile_skills_delete_dialog_cancel"),
                    "delete_dialog_submit" => __("profiles.update_profile_skills_delete_dialog_submit"),
                    "nl" => __("general.nl"),
                    "en" => __("general.en"),
                ])
            ])
        ]);
    }

    public function postUpdateProfile(UpdateProfileRequest $request)
    {
        // Update the user's profile
        Users::updateProfileFromRequest($request);

        // Flash message & redirect to view profile page
        flash(__('general.saved_changes'))->success();
        return redirect()->route("profile");
    }

    public function getFollow($slug)
    {
        // Grab the user we want to follow
        $user = Users::findBySlug($slug);
        if (!$user)
        {
            flash(__('profiles.user_not_found'))->error();
            return redirect()->route("memberlist");
        }

        // Follow the user
        auth()->user()->follow($user);

        // Fire event
        event(new UserFollowsUser(auth()->user(), $user));

        // Flash message & redirect to the user's profile
        flash(__("followers.follow_success", ["user" => $user->formattedName]))->success();
        return redirect()->route("profile", $user->slug);
    }

    public function getUnfollow($slug)
    {
        // Grab the user we want to unfollow
        $user = Users::findBySlug($slug);
        if (!$user)
        {
            flash(__('profiles.user_not_found'))->error();
            return redirect()->route("memberlist");
        }

        // Unfollow the user
        Auth::user()->unfollow($user);

        // Fire event
        event(new UserUnfollowsUser(auth()->user(), $user));

        // Flash message & redirect to the user's profile
        flash(__("followers.unfollow_success", ["user" => $user->formattedName]))->success();
        return redirect()->route("profile", $user->slug);
    }

    public function getRequestAccessToEmail($slug)
    {
        // Grab the user we want to send the request to
        $user = Users::findBySlug($slug);
        if (!$user)
        {
            flash(__('profiles.user_not_found'))->error();
            return redirect()->route("memberlist");
        }

        // Send view email request to the target user
        ViewEmailRequests::sendRequest($user);

        // Flash message & redirect to the user's profile
        flash(__("profiles.profile_email_request_sent_message", ["name" => $user->formattedName]))->success();
        return redirect()->route("profile", $user->slug);
    }

    public function getAcceptAccessEmailRequest($messageUuid, $requestUuid)
    {
        // Grab the message containing the request
        $message = Messages::findByUuid($messageUuid);
        if (!$message)
        {
            flash(__("messages.message_not_found"))->error();
            return redirect()->back();
        }

        // Grab the request we want to accept
        $request = ViewEmailRequests::findByUuid($requestUuid);
        if (!$request)
        {
            flash(__("profiles.profile_email_request_not_found"))->error();
            return redirect()->back();
        }

        // Accept the view email request
        ViewEmailRequests::accept($message, $request);

        // Flash message & redirect back
        flash(__("profiles.profile_email_request_accepted"))->success();
        return redirect()->back();
    }
    
    public function getRejectAccessEmailRequest($messageUuid, $requestUuid)
    {
        // Grab the message containing the request
        $message = Messages::findByUuid($messageUuid);
        if (!$message)
        {
            flash(__("messages.message_not_found"))->error();
            return redirect()->back();
        }

        // Grab the request we want to reject
        $request = ViewEmailRequests::findByUuid($requestUuid);
        if (!$request)
        {
            flash(__("profiles.profile_email_request_not_found"))->error();
            return redirect()->back();
        }

        // Reject the request
        ViewEmailRequests::reject($message, $request);

        // Flash message & redirect back
        flash(__("profiles.profile_email_request_rejected"))->success();
        return redirect()->back();
    }
}