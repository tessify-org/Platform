<?php

namespace App\Http\Controllers\Projects;

use Auth;
use Tags;
use Users;
use Tasks;
use Skills;
use Comments;
use Projects;
use Reputation;
use Ministries;
use WorkMethods;
use Organizations;
use ReviewRequests;
use ProjectPhases;
use ProjectStatuses;
use ProjectResources;
use ProjectCategories;
use OrganizationDepartments;
use App\Http\Controllers\Controller;
use App\Events\Users\UserFollowsProject;
use App\Events\Users\UserUnfollowsProject;
use App\Events\Users\UserDeletedProject;
use App\Events\Projects\ProjectDeleted;
use App\Http\Requests\Projects\CreateProjectRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Http\Requests\Projects\DeleteProjectRequest;
use App\Http\Requests\Projects\AskQuestionRequest;

class ProjectController extends Controller
{
    public function getGetStarted()
    {
        // Render the get started page
        return view("pages.projects.get-started");
    }

    public function getOverview()
    {
        // Render the project overview page
        return view("pages.projects.overview", [
            "projects" => Projects::getAllPreloaded(),
            "statuses" => ProjectStatuses::getAll(),
            "categories" => ProjectCategories::getAll(),
            "strings" => collect([
                "no_records" => __("projects.overview_no_projects"),
                "search_title" => __("projects.overview_sidebar_search"),
                "status_title" => __("projects.overview_sidebar_statuses"),
                "category_title" => __("projects.overview_sidebar_categories"),
                "filters" => __("tasks.overview_filters"),
                "filters_description" => __("tasks.overview_filters_description"),
                "filters_view_results" => __("tasks.overview_filters_view_results"),
            ])
        ]);
    }

    public function getView($slug)
    {
        // Grab the project we want to view
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Render the view project page
        return view("pages.projects.view", [
            "project" => $project,
            "users" => Users::getAllPreloaded(),
            "userHasPendingReviewRequest" => ReviewRequests::hasOutstandingRequestsFor($project),
            "user" => Users::current(),
            "author" => Projects::getAuthor($project),
            "resources" => Projects::getResources($project),
        ]);
    }
    
    public function getViewRoles($slug)
    {
        // Grab the project we want to view
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Render the project tasks overview page
        return view("pages.projects.roles", [
            "project" => $project,
        ]);
    }

    public function getViewComments($slug)
    {
        // Grab the project we want to view
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Render the task comments page
        return view("pages.projects.comments", [
            "user" => Users::current(),
            "project" => $project,
            "comments" => Comments::getAllForProject($project),
            "strings" => collect([
                "title" => __("comments.title"),
                "no_records" => __("comments.no_comments_project"),
                "create_title" => __("comments.create_title"),
                "create_submit" => __("comments.create_submit"),
                "create_cancel" => __("comments.create_cancel"),
                "update_title" => __("comments.update_title"),
                "update_cancel" => __("comments.update_cancel"),
                "update_submit" => __("comments.update_submit"),
                "delete_title" => __("comments.delete_title"),
                "delete_text" => __("comments.delete_text"),
                "delete_cancel" => __("comments.delete_cancel"),
                "delete_submit" => __("comments.delete_submit"),
            ]),
            "apiEndpoints" => collect([
                "create" => route("api.comments.create.post"),
                "update" => route("api.comments.update.post"),
                "delete" => route("api.comments.delete.post"),
            ]),
        ]);
    }
    
    public function getViewResources($slug)
    {
        // Grab the project we want to view
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Render the task comments page
        return view("pages.projects.resources", [
            "project" => $project,
            "resources" => $project->resources,
        ]);
    }

    public function getCreate()
    {
        // Render the create project page
        return view("pages.projects.create", [
            "phases" => ProjectPhases::getAll(),
            "ministries" => Ministries::getAll(),
            "organizations" => Organizations::getAll(),
            "departments" => OrganizationDepartments::getAll(),
            "statuses" => Projectstatuses::getAll(),
            "categories" => ProjectCategories::getAll(),
            "workMethods" => WorkMethods::getAll(),
            "skills" => Skills::getAll(),
            "tags" => Tags::getAll(),
            "oldInput" => collect([
                "project_status_id" => old("project_status_id"),
                "project_category" => old("project_category"),
                "project_phase" => old("project_phase"),
                "project_code" => old("project_code"),
                "ministry_id" => old("ministry_id"),
                "organization_id" => old("organization_id"),
                "department" => old("department"),
                "title" => old("title"),
                "slogan_nl" => old("slogan_nl"),
                "slogan_en" => old("slogan_en"),
                "description_nl" => old("description_nl"),
                "description_en" => old("description_en"),
                "starts_at" => old("starts_at"),
                "ends_at" => old("ends_at"),
                "has_deadline" => old("has_deadline"),
                "budget" => old("budget"),
                "tags" => old("tags"),
            ]),
            "strings" => collect([
                "optional" => __("projects.form_optional"),
                "general_title" => __("projects.form_general_title"),
                "general_description" => __("projects.form_general_description"),
                "status_title" => __("projects.form_status_title"),
                "status_description" => __("projects.form_status_description"),
                "ownership_title" => __("projects.form_ownership_title"),
                "ownership_description" => __("projects.form_ownership_description"),
                "formatting_title" => __("projects.form_formatting_title"),
                "formatting_description" => __("projects.form_formatting_description"),
                "title" => __("projects.form_title"),
                "title_hint" => __("projects.form_title_hint"),
                "slogan" => __("projects.form_slogan"),
                "slogan_hint" => __("projects.form_slogan_hint"),
                "description" => __("projects.form_description"),
                "description_hint" => __("projects.form_description_hint"),
                "header_image" => __("projects.form_header_image"),
                "category" => __("projects.form_category"),
                "work_method" => __("projects.form_work_method"),
                "status" => __("projects.form_status"),
                "no_statuses" => __("projects.form_no_statuses"),
                "has_tasks" => __("projects.form_has_tasks"),
                "has_deadline" => __("projects.form_has_deadline"),
                "start_date" => __("projects.form_start_date"),
                "deadline" => __("projects.form_deadline"),
                "has_budget" => __("projects.form_has_budget"),
                "budget" => __("projects.form_budget"),
                "ministry" => __("projects.form_ministry"),
                "no_ministries" => __("projects.form_no_ministries"),
                "organization" => __("projects.form_organization"),
                "no_organizations" => __("projects.form_no_organizations"),
                "department" => __("projects.form_department"),
                "project_code" => __("projects.form_project_code"),
                "project_phase" => __("projects.form_project_phase"),
                "tags" => __("projects.form_tags"),
                "back" => __("projects.update_back"),
                "submit" => __("projects.update_submit"),
                "resource_strings" => [
                    "no_records" => __("projects.resources_field_no_resources"),
                    "add_button" => __("projects.resources_field_add_button"),
                    "form_title" => __("projects.resources_field_form_title"),
                    "form_description" => __("projects.resources_field_form_description"),
                    "form_file" => __("projects.resources_field_form_file"),
                    "create_dialog_title" => __("projects.resources_field_create_dialog_title"),
                    "create_dialog_cancel" => __("projects.resources_field_create_dialog_cancel"),
                    "create_dialog_submit" => __("projects.resources_field_create_dialog_submit"),
                    "update_dialog_title" => __("projects.resources_field_update_dialog_title"),
                    "update_dialog_cancel" => __("projects.resources_field_update_dialog_cancel"),
                    "update_dialog_submit" => __("projects.resources_field_update_dialog_submit"),
                    "delete_dialog_title" => __("projects.resources_field_update_dialog_title"),
                    "delete_dialog_text" => __("projects.resources_field_delete_dialog_text"),
                    "delete_dialog_cancel" => __("projects.resources_field_delete_dialog_cancel"),
                    "delete_dialog_submit" => __("projects.resources_field_delete_dialog_submit"),
                ],
                "nl" => __("general.nl"),
                "en" => __("general.en"),
            ]),
        ]);
    }

    public function postCreate(CreateProjectRequest $request)
    {
        // Create the project
        $project = Projects::createFromRequest($request);

        // Flash message & redirect to project view
        flash(__("projects.project_created"))->success();
        return redirect()->route("projects.view", $project->slug);
    }
    
    public function getEdit($slug)
    {
        // Grab the project we want to update
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {   
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Render the update project page
        return view("pages.projects.edit", [
            "project" => $project,
            "phases" => ProjectPhases::getAll(),
            "ministries" => Ministries::getAll(),
            "organizations" => Organizations::getAll(),
            "departments" => OrganizationDepartments::getAll(),
            "statuses" => Projectstatuses::getAll(),
            "categories" => ProjectCategories::getAll(),
            "workMethods" => WorkMethods::getAll(),
            "skills" => Skills::getAll(),
            "tags" => Tags::getAll(),
            "oldInput" => collect([
                "project_status_id" => old("project_status_id"),
                "project_category" => old("project_category"),
                "project_phase" => old("project_phase"),
                "project_code" => old("project_code"),
                "ministry_id" => old("ministry_id"),
                "organization_id" => old("organization_id"),
                "department" => old("department"),
                "title" => old("title"),
                "slogan_nl" => old("slogan_nl"),
                "slogan_en" => old("slogan_en"),
                "description_nl" => old("description_nl"),
                "description_en" => old("description_en"),
                "starts_at" => old("starts_at"),
                "ends_at" => old("ends_at"),
                "has_deadline" => old("has_deadline"),
                "budget" => old("budget"),
                "tags" => old("tags"),
            ]),
            "strings" => collect([
                "optional" => __("projects.form_optional"),
                "general_title" => __("projects.form_general_title"),
                "general_description" => __("projects.form_general_description"),
                "status_title" => __("projects.form_status_title"),
                "status_description" => __("projects.form_status_description"),
                "ownership_title" => __("projects.form_ownership_title"),
                "ownership_description" => __("projects.form_ownership_description"),
                "formatting_title" => __("projects.form_formatting_title"),
                "formatting_description" => __("projects.form_formatting_description"),
                "title" => __("projects.form_title"),
                "title_hint" => __("projects.form_title_hint"),
                "slogan" => __("projects.form_slogan"),
                "slogan_hint" => __("projects.form_slogan_hint"),
                "description" => __("projects.form_description"),
                "description_hint" => __("projects.form_description_hint"),
                "header_image" => __("projects.form_header_image"),
                "category" => __("projects.form_category"),
                "status" => __("projects.form_status"),
                "has_tasks" => __("projects.form_has_tasks"),
                "has_deadline" => __("projects.form_has_deadline"),
                "start_date" => __("projects.form_start_date"),
                "deadline" => __("projects.form_deadline"),
                "has_budget" => __("projects.form_has_budget"),
                "budget" => __("projects.form_budget"),
                "ministry" => __("projects.form_ministry"),
                "no_ministries" => __("projects.form_no_ministries"),
                "organization" => __("projects.form_organization"),
                "no_organizations" => __("projects.form_no_organizations"),
                "department" => __("projects.form_department"),
                "project_code" => __("projects.form_project_code"),
                "project_phase" => __("projects.form_project_phase"),
                "tags" => __("projects.form_tags"),
                "back" => __("projects.update_back"),
                "submit" => __("projects.update_submit"),
                "resource_strings" => [
                    "no_records" => __("projects.resources_field_no_resources"),
                    "add_button" => __("projects.resources_field_add_button"),
                    "form_title" => __("projects.resources_field_form_title"),
                    "form_description" => __("projects.resources_field_form_description"),
                    "form_file" => __("projects.resources_field_form_file"),
                    "create_dialog_title" => __("projects.resources_field_create_dialog_title"),
                    "create_dialog_cancel" => __("projects.resources_field_create_dialog_cancel"),
                    "create_dialog_submit" => __("projects.resources_field_create_dialog_submit"),
                    "update_dialog_title" => __("projects.resources_field_update_dialog_title"),
                    "update_dialog_cancel" => __("projects.resources_field_update_dialog_cancel"),
                    "update_dialog_submit" => __("projects.resources_field_update_dialog_submit"),
                    "delete_dialog_title" => __("projects.resources_field_update_dialog_title"),
                    "delete_dialog_text" => __("projects.resources_field_delete_dialog_text"),
                    "delete_dialog_cancel" => __("projects.resources_field_delete_dialog_cancel"),
                    "delete_dialog_submit" => __("projects.resources_field_delete_dialog_submit"),
                ],
                "nl" => __("general.nl"),
                "en" => __("general.en"),
            ]),
        ]);
    }

    public function postEdit(UpdateProjectRequest $request, $slug)
    {
        // Grab the project we want to update
        $project = Projects::findBySlug($slug);
        if (!$project)
        {   
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Update the project
        $project = Projects::updateFromRequest($project, $request);

        // Flash message & redirect to project view
        flash(__("general.saved_changes"))->success();
        return redirect()->route("projects.view", $project->slug);
    }

    public function getDelete($slug)
    {
        // Grab the project we want to delete
        $project = Projects::findBySlug($slug);
        if (!$project)
        {   
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Render the delete project page
        return view("pages.projects.delete", [
            "project" => $project,
        ]);
    }

    public function postDelete(DeleteProjectRequest $request, $slug)
    {
        // Grab the project we want to delete
        $project = Projects::findBySlug($slug);
        if (!$project)
        {   
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Fire events (before actually deleting the project so data is still available; maybe implement soft-deletes to ensure it's always available)
        event(new UserDeletedProject(auth()->user(), $project));
        event(new ProjectDeleted($project));

        // Delete the project
        $project->delete();

        // Flash message & redirect to overview
        flash(__("projects.project_deleted"))->success();
        return redirect()->route("projects");
    }

    public function getSubscribe($slug)
    {
        // Grab the project we want to subscribe to
        $project = Projects::findBySlug($slug);
        if (!$project)
        {   
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Subscribe user to the project
        Auth::user()->subscribe($project);

        // Fire event
        event(new UserFollowsProject(auth()->user(), $project));

        // Flash message & redirect to view
        flash(__("projects.view_subscribed"))->success();
        return redirect()->route("projects.view", $slug);
    }

    public function getUnsubscribe($slug)
    {
        // Grab the project we want to unsubscribe from
        $project = Projects::findBySlug($slug);
        if (!$project)
        {   
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Unsubscribe current user from the project
        Auth::user()->unsubscribe($project);

        // Fire event
        event(new UserUnfollowsProject(auth()->user(), $project));

        // Flash message & redirect to view
        flash(__("projects.view_subscribed"))->success();
        return redirect()->route("projects.view", $slug);
    }

    public function getInviteFriend($slug, $userSlug = null)
    {
        // Grab the task we want to complete
        $project = Projects::findBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }
        
        // Make sure we received a target user
        if (is_null($userSlug))
        {
            flash(__("messages.invitation_failed"))->error();
            return redirect()->route("projects.view", $project->slug);
        }

        // Grab the target user
        $user = Users::findBySlug($userSlug);
        if (!$user)
        {
            flash(__("messages.invitation_failed"))->error();
            return redirect()->route("projects.view", $project->slug);
        }

        // Send invitation message
        Messages::sendInviteToProjectMessage($user, $project);

        // Flash message and redirect back to view task page
        flash(__("messages.invitation_sent"))->success();
        return redirect()->route("projects.view", $project->slug);
    }

    public function postAskQuestion(AskQuestionRequest $request, $slug)
    {
        // Grab the task we want to complete
        $project = Projects::findBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Grab currently logged in user
        $user = Users::current();

        // Send ask question message
        Messages::sendAskProjectQuestionMessage($user, $project, $request->question);

        // Flash message and redirect back to view task page
        return response()->json(["status" => "success"]);
    }
}