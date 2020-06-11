<?php

namespace App\Http\Controllers\Tasks;

use Auth;
use Tags;
use Users;
use Tasks;
use Groups;
use Skills;
use Comments;
use ReviewRequests;
use Projects;
use Messages;
use Reputation;
use Ministries;
use Organizations;
use OrganizationDepartments;
use TaskStatuses;
use TaskCategories;
use TaskSeniorities;
use TaskProgressReports;
use TaskProgressReportReviews;

use App\Http\Controllers\Controller;
use App\Events\Tasks\TaskAssigned;
use App\Events\Tasks\TaskUnassigned;
use App\Events\Users\UserFollowsTask;
use App\Events\Users\UserUnfollowsTask;
use App\Http\Requests\Tasks\CreateTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Http\Requests\Tasks\DeleteTaskRequest;
use App\Http\Requests\Tasks\AbandonTaskRequest;
use App\Http\Requests\Tasks\AskQuestionRequest;

class TaskController extends Controller
{
    public function getOverview()
    {
        // Render the task overview page
        return view("pages.tasks.overview", [
            "tasks" => Tasks::getAllPreloaded(),
            "skills" => Skills::getAll(),
            "statuses" => TaskStatuses::getAll(),
            "categories" => TaskCategories::getAll(),
            "seniorities" => TaskSeniorities::getAll(),
            "strings" => collect([
                "no_records" => __("tasks.overview_no_tasks"),
                "search" => __("tasks.overview_search"),
                "status" => __("tasks.overview_status"),
                "skills" => __("tasks.overview_skills"),
                "categories" => __("tasks.overview_categories"),
                "seniorities" => __("tasks.overview_seniorities"),
                "filters" => __("tasks.overview_filters"),
                "filters_description" => __("tasks.overview_filters_description"),
                "filters_view_results" => __("tasks.overview_filters_view_results"),
            ]),
        ]);
    }

    public function getView($slug)
    {
        // Grab the task we want to view
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Render the view task page
        return view("pages.tasks.view", [
            "task" => $task,
            "users" => Users::getAllPreloaded(),
            "userHasPendingReviewRequest" => ReviewRequests::hasOutstandingRequestsFor($task),
            "requiredSkillsStrings" => collect([
                "description" => __("tasks.view_required_skills_description"),
                "mastery" => __("tasks.view_required_skills_required_mastery"),
                "missing_description" => __("tasks.view_required_skills_missing_description"),
            ]),
            "locale" => app()->getLocale(),
        ]);
    }
    
    public function getComments($slug)
    {
        // Grab the task we want to view
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Render the task comments page
        return view("pages.tasks.comments", [
            "user" => Users::current(),
            "task" => $task,
            "comments" => Comments::getAllForTask($task),
            "strings" => collect([
                "title" => __("comments.title"),
                "no_records" => __("comments.no_comments_task"),
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

    public function getCreate($slug = null)
    {
        // Grab the parent project if a slug was provided
        $project = null;
        if (!is_null($slug))
        {
            $project = Projects::findPreloadedBySlug($slug);
            if (!$project)
            {
                flash(__("projects.project_not_found"))->error();
                return redirect()->route("projects");
            }
        }

        // Render the create task page
        return view("pages.tasks.create", [
            "project" => $project,
            "projects" => Projects::getAllForUser(),
            "skills" => Skills::getAll(),
            "categories" => TaskCategories::getAll(),
            "seniorities" => TaskSeniorities::getAll(),
            "ministries" => Ministries::getAll(),
            "organizations" => Organizations::getAll(),
            "departments" => OrganizationDepartments::getAll(),
            "groups" => Groups::getMyGroups(),
            "tags" => Tags::getAll(),
            "oldInput" => collect([
                "project_id" => old("project_id"),
                "group" => old("group"),
                "organization" => old("organization"),
                "department" => old("department"),
                "task_seniority_id" => old("task_seniority_id"),
                "task_category" => old("task_category"),
                "title_nl" => old("title_nl"),
                "title_en" => old("title_en"),
                "description_nl" => old("description_nl"),
                "description_en" => old("description_en"),
                "tags" => old("tags"),
                "complexity" => old("complexity"),
                "estimated_hours" => old("estimated_hours"),
                "required_skills" => old("required_skills"),
                "urgency" => old("urgency"),
                "tags" => old("tags"),
                "ends_at" => old("ends_at"),
                "has_deadline" => old("has_deadline"),
            ]),
            "strings" => collect([
                "parent_project_title" => __("tasks.form_parent_project_title"),
                "parent_project_description" => __("tasks.form_parent_project_description"),
                "general_title" => __("tasks.form_general_title"),
                "general_description" => __("tasks.form_general_description"),
                "ownership_title" => __("tasks.form_ownership_title"),
                "ownership_description" => __("tasks.form_ownership_description"),
                "formatting_title" => __("tasks.form_formatting_title"),
                "formatting_description" => __("tasks.form_formatting_description"),
                "skills_title" => __("tasks.form_skills_title"),
                "skills_description" => __("tasks.form_skills_description"),
                "parent_type" => __("tasks.form_parent_type"),
                "parent_type_me" => __("tasks.form_parent_type_me"),
                "parent_type_organization" => __("tasks.form_parent_type_organization"),
                "parent_type_group" => __("tasks.form_parent_type_group"),
                "group" => __("tasks.form_group"),
                "group_no_groups" => __("tasks.form_group_no_groups"),
                "header_image" => __("tasks.form_header_image"),
                "has_deadline" => __("tasks.form_has_deadline"),
                "ends_at" => __("tasks.form_ends_at"),
                "ministry" => __("tasks.form_ministry"),
                "no_ministries" => __("tasks.form_no_ministries"),
                "organization" => __("tasks.form_organization"),
                "no_organizations" => __("tasks.form_no_organizations"),
                "department" => __("tasks.form_department"),
                "status" => __("tasks.create_form_status"),
                "project" => __("tasks.form_project"),
                "no_projects" => __("tasks.form_no_projects"),
                "dont_associate_with_project" => __("tasks.form_dont_associate_project"),
                "category" => __("tasks.create_form_category"),
                "seniority" => __("tasks.create_form_seniority"),
                "title" => __("tasks.create_form_title"),
                "description" => __("tasks.create_form_description"),
                "complexity" => __("tasks.create_form_complexity"),
                "estimated_hours" => __("tasks.create_form_estimated_hours"),
                "realized_hours" => __("tasks.create_form_realized_hours"),
                "select_category" => __("tasks.create_form_select_category"),
                "no_categories" => __("tasks.create_form_no_categories"),
                "select_seniority" => __("tasks.create_form_select_seniority"),
                "no_seniorities" => __("tasks.create_form_no_seniorities"),
                "select_status" => __("tasks.create_form_select_status"),
                "no_statuses" => __("tasks.create_form_no_statuses"),
                "required_skills" => __("tasks.create_form_required_skills"),
                "urgency" => __("tasks.create_form_urgency"),
                "tags" => __("tasks.create_form_tags"),
                "back" => __("tasks.create_back"),
                "submit" => __("tasks.create_submit"),
                "urgency_low" => __("general.urgency_low"),
                "urgency_normal" => __("general.urgency_normal"),
                "urgency_high" => __("general.urgency_high"),
                "required_skills_strings" => [
                    "title" => __("tasks.required_skills_title"),
                    "no_records" => __("tasks.required_skills_no_records"),
                    "add_button" => __("tasks.required_skills_add_button"),
                    "view_title" => __("tasks.required_skills_view_title"),
                    "view_skill" => __("tasks.required_skills_view_skill"),
                    "view_required_mastery" => __("tasks.required_skills_view_required_mastery"),
                    "view_description" => __("tasks.required_skills_view_description"),
                    "view_edit" => __("tasks.required_skills_view_edit"),
                    "view_delete" => __("tasks.required_skills_view_delete"),
                    "form_skill" => __("tasks.required_skills_form_skill"),
                    "form_required_mastery" => __("tasks.required_skills_form_required_mastery"),
                    "form_description" => __("tasks.required_skills_form_description"),
                    "add_title" => __("tasks.required_skills_add_title"),
                    "add_cancel" => __("tasks.required_skills_add_cancel"),
                    "add_submit" => __("tasks.required_skills_add_submit"),
                    "edit_title" => __("tasks.required_skills_edit_title"),
                    "edit_cancel" => __("tasks.required_skills_edit_cancel"),
                    "edit_submit" => __("tasks.required_skills_edit_submit"),
                    "delete_title" => __("tasks.required_skills_delete_title"),
                    "delete_text" => __("tasks.required_skills_delete_text"),
                    "delete_cancel" => __("tasks.required_skills_delete_cancel"),
                    "delete_submit" => __("tasks.required_skills_delete_submit"),
                    "nl" => __("general.nl"),
                    "en" => __("general.en"),
                ],
                "nl" => __("general.nl"),
                "en" => __("general.en"),
            ]),
        ]);
    }

    public function postCreate(CreateTaskRequest $request)
    {
        // Create the task
        $task = Tasks::createFromRequest($request);

        // Flash message && redirect to view task page
        flash(__("tasks.created"))->success();
        return redirect()->route("tasks.view", ["slug" => $task->slug]);
    }

    public function getEdit($slug)
    {
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("projects.tasks", $project->slug);
        }
        
        return view("pages.tasks.edit", [
            "task" => $task,
            "projects" => Projects::getAllForUser(),
            "skills" => Skills::getAll(),
            "statuses" => TaskStatuses::getAll(),
            "categories" => TaskCategories::getAll(),
            "seniorities" => TaskSeniorities::getAll(),
            "ministries" => Ministries::getAll(),
            "organizations" => Organizations::getAll(),
            "departments" => OrganizationDepartments::getAll(),
            "groups" => Groups::getMyGroups(),
            "tags" => Tags::getAll(),
            "oldInput" => collect([
                "project_id" => old("project_id"),
                "group" => old("group"),
                "organization" => old("organization"),
                "department" => old("department"),
                "task_status_id" => old("task_status_id"),
                "task_seniority_id" => old("task_seniority_id"),
                "task_category" => old("task_category"),
                "title_nl" => old("title_nl"),
                "title_en" => old("title_en"),
                "description_nl" => old("description_nl"),
                "description_en" => old("description_en"),
                "tags" => old("tags"),
                "complexity" => old("complexity"),
                "estimated_hours" => old("estimated_hours"),
                "realized_hours" => old("realized_hours"),
                "required_skills" => old("required_skills"),
                "urgency" => old("urgency"),
                "tags" => old("tags"),
                "ends_at" => old("ends_at"),
                "has_deadline" => old("has_deadline"),
            ]),
            "strings" => collect([
                "parent_project_title" => __("tasks.form_parent_project_title"),
                "parent_project_description" => __("tasks.form_parent_project_description"),
                "general_title" => __("tasks.form_general_title"),
                "general_description" => __("tasks.form_general_description"),
                "ownership_title" => __("tasks.form_ownership_title"),
                "ownership_description" => __("tasks.form_ownership_description"),
                "formatting_title" => __("tasks.form_formatting_title"),
                "formatting_description" => __("tasks.form_formatting_description"),
                "skills_title" => __("tasks.form_skills_title"),
                "skills_description" => __("tasks.form_skills_description"),
                "parent_type" => __("tasks.form_parent_type"),
                "parent_type_me" => __("tasks.form_parent_type_me"),
                "parent_type_organization" => __("tasks.form_parent_type_organization"),
                "parent_type_group" => __("tasks.form_parent_type_group"),
                "group" => __("tasks.form_group"),
                "group_no_groups" => __("tasks.form_group_no_groups"),
                "header_image" => __("tasks.form_header_image"),
                "has_deadline" => __("tasks.form_has_deadline"),
                "ends_at" => __("tasks.form_ends_at"),
                "ministry" => __("tasks.form_ministry"),
                "no_ministries" => __("tasks.form_no_ministries"),
                "organization" => __("tasks.form_organization"),
                "no_organizations" => __("tasks.form_no_organizations"),
                "department" => __("tasks.form_department"),
                "status" => __("tasks.create_form_status"),
                "project" => __("tasks.form_project"),
                "no_projects" => __("tasks.form_no_projects"),
                "dont_associate_with_project" => __("tasks.form_dont_associate_project"),
                "category" => __("tasks.create_form_category"),
                "seniority" => __("tasks.create_form_seniority"),
                "title" => __("tasks.create_form_title"),
                "description" => __("tasks.create_form_description"),
                "complexity" => __("tasks.create_form_complexity"),
                "estimated_hours" => __("tasks.create_form_estimated_hours"),
                "realized_hours" => __("tasks.create_form_realized_hours"),
                "select_category" => __("tasks.create_form_select_category"),
                "no_categories" => __("tasks.create_form_no_categories"),
                "select_seniority" => __("tasks.create_form_select_seniority"),
                "no_seniorities" => __("tasks.create_form_no_seniorities"),
                "select_status" => __("tasks.create_form_select_status"),
                "no_statuses" => __("tasks.create_form_no_statuses"),
                "required_skills" => __("tasks.create_form_required_skills"),
                "urgency" => __("tasks.create_form_urgency"),
                "tags" => __("tasks.create_form_tags"),
                "back" => __("tasks.create_back"),
                "submit" => __("tasks.create_submit"),
                "urgency_low" => __("general.urgency_low"),
                "urgency_normal" => __("general.urgency_normal"),
                "urgency_high" => __("general.urgency_high"),
                "required_skills_strings" => [
                    "title" => __("tasks.required_skills_title"),
                    "no_records" => __("tasks.required_skills_no_records"),
                    "add_button" => __("tasks.required_skills_add_button"),
                    "view_title" => __("tasks.required_skills_view_title"),
                    "view_skill" => __("tasks.required_skills_view_skill"),
                    "view_required_mastery" => __("tasks.required_skills_view_required_mastery"),
                    "view_description" => __("tasks.required_skills_view_description"),
                    "view_edit" => __("tasks.required_skills_view_edit"),
                    "view_delete" => __("tasks.required_skills_view_delete"),
                    "form_skill" => __("tasks.required_skills_form_skill"),
                    "form_required_mastery" => __("tasks.required_skills_form_required_mastery"),
                    "form_description" => __("tasks.required_skills_form_description"),
                    "add_title" => __("tasks.required_skills_add_title"),
                    "add_cancel" => __("tasks.required_skills_add_cancel"),
                    "add_submit" => __("tasks.required_skills_add_submit"),
                    "edit_title" => __("tasks.required_skills_edit_title"),
                    "edit_cancel" => __("tasks.required_skills_edit_cancel"),
                    "edit_submit" => __("tasks.required_skills_edit_submit"),
                    "delete_title" => __("tasks.required_skills_delete_title"),
                    "delete_text" => __("tasks.required_skills_delete_text"),
                    "delete_cancel" => __("tasks.required_skills_delete_cancel"),
                    "delete_submit" => __("tasks.required_skills_delete_submit"),
                    "nl" => __("general.nl"),
                    "en" => __("general.en"),
                ],
                "nl" => __("general.nl"),
                "en" => __("general.en"),
            ]),
        ]);
    }

    public function postEdit(UpdateTaskRequest $request, $slug)
    {
        $task = Tasks::findBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        Tasks::updateFromRequest($task, $request);

        flash(__("tasks.edited"))->success();
        return redirect()->route("tasks.view", ["slug" => $task->slug]);
    }

    public function getDelete($slug)
    {
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        return view("pages.tasks.delete", [
            "task" => $task,
        ]);
    }

    public function postDelete(DeleteTaskRequest $request, $slug)
    {
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        $project = $task->project;

        $task->delete();

        flash(__("projects.tasks_deleted"))->error();
        if ($project) {
            return redirect()->route("projects.tasks", $project->slug);
        } else {
            return redirect()->route("tasks");
        }
    }

    public function getAssignToSelf($slug)
    {
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        Tasks::assignToUser($task);

        event(new TaskAssigned($task));

        flash(__("tasks.assign_to_self_success"))->success();
        return redirect()->route("tasks.view", ["slug" => $task->slug]);
    }

    public function getAbandon($slug)
    {
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        return view("pages.tasks.abandon", [
            "task" => $task,
            "strings" => collect([
                "text" => __("tasks.abandon_text", ["title" => $task->title]),
                "reason" => __("tasks.abandon_reason"),
                "reason_placeholder" => __("tasks.abandon_reason_placeholder"),
                "cancel" => __("tasks.abandon_cancel"),
                "submit" => __("tasks.abandon_confirm"),
            ]),
            "oldInput" => collect([
                "reason" => old("reason"),
            ]),
        ]);
    }

    public function postAbandon(AbandonTaskRequest $request, $slug)
    {
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        $task = Tasks::unassignUser($task, $request->reason);
        
        event(new TaskUnassigned($task));

        flash(__("tasks.abandon_success"))->success();
        return redirect()->route("tasks.view", ["slug" => $task->slug]);
    }

    public function getSubscribe($slug)
    {
        // Grab the task we want to subscribe to
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Subscribe to the user to the task
        Auth::user()->subscribe($task);

        // Fire events
        event(new UserFollowsTask(auth()->user(), $task));

        // Flash message & redirect to the view task page
        flash(__("tasks.view_subscribed"))->success();
        return redirect()->route("tasks.view", ["slug" => $task->slug]);
    }

    public function getUnsubscribe($slug)
    {
        // Grab the task we want to unsubscribe from
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Unsubscribe the user from the task
        Auth::user()->unsubscribe($task);

        // Fire events
        event(new UserUnfollowsTask(auth()->user(), $task));

        // Flash message & redirect to the view page
        flash(__("tasks.view_unsubscribed"))->success();
        return redirect()->route("tasks.view", ["slug" => $task->slug]);
    }

    public function getInviteFriend($slug, $userSlug = null)
    {
        // Grab the task we want to complete
        $task = Tasks::findBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }
        
        // Make sure we received a target user
        if (is_null($userSlug))
        {
            flash(__("messages.invitation_failed"))->error();
            return redirect()->route("tasks.view", $task->slug);
        }

        // Grab the target user
        $user = Users::findBySlug($userSlug);
        if (!$user)
        {
            flash(__("messages.invitation_failed"))->error();
            return redirect()->route("tasks.view", $task->slug);
        }

        // Send invitation message
        Messages::sendInviteToTaskMessage($user, $task);

        // Flash message and redirect back to view task page
        flash(__("messages.invitation_sent"))->success();
        return redirect()->route("tasks.view", $task->slug);
    }

    public function postAskQuestion(AskQuestionRequest $request, $slug)
    {
        // Grab the task we want to complete
        $task = Tasks::findBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Grab currently logged in user
        $user = Users::current();

        // Send ask question message
        Messages::sendAskTaskQuestionMessage($user, $task, $request->question);

        // Flash message and redirect back to view task page
        return response()->json(["status" => "success"]);
    }
}