<?php

namespace App\Services\ModelServices;

use Auth;
use Tags;
use Dates;
use Users;
use Tasks;
use Skills;
use Reviews;
use Messages;
use Comments;
use Uploader;
use TeamRoles;
use TeamMembers;
use WorkMethods;
use Ministries;
use Organizations;
use OrganizationDepartments;
use ProjectPhases;
use ProjectStatuses;
use ProjectResources;
use ProjectCategories;
use TeamMemberApplications;

use App\Models\User;
use App\Models\Project;
use App\Models\TeamRole;
use App\Models\TeamMemberApplication;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Events\Users\UserCreatedProject;
use App\Events\Users\UserUpdatedProject;
use App\Events\Users\UserDeletedProject;
use App\Events\Projects\ProjectCreated;
use App\Events\Projects\ProjectUpdated;
use App\Events\Projects\ProjectDeleted;
use App\Events\Projects\ProjectCompleted;
use App\Http\Requests\Projects\CreateProjectRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Http\Requests\Projects\Teams\Applications\ApplyForTeamRoleRequest;

class ProjectService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Project";
    }
    
    public function preload($instance)
    {
        // Convert header image url from relative to absolute (so it can be used in vue components)
        $instance->header_image_url = asset($instance->header_image_url);

        // Load the project's resources
        $instance->resources = ProjectResources::getAllPreloadedForProject($instance);
        $instance->team_roles = TeamRoles::getAllForProject($instance);
        $instance->status = ProjectStatuses::findForProject($instance);
        $instance->author = Users::findAuthorForProject($instance);
        $instance->category = ProjectCategories::findForProject($instance);
        $instance->work_method = WorkMethods::findForProject($instance);
        $instance->phase = ProjectPhases::findForProject($instance);
        $instance->team_members = TeamMembers::getAllForProject($instance);
        $instance->team_member_applications = TeamMemberApplications::getAllForProject($instance);
        $instance->tasks = Tasks::getAllForProject($instance);
        $instance->tags = Tags::getAllForProject($instance);

        // Load ownership relationships
        $instance->ministry = Ministries::findForProject($instance);
        $instance->organization = Organizations::findForProject($instance);
        $instance->department = OrganizationDepartments::findForProject($instance);

        // Format the dates
        $instance->formatted_starts_at = is_null($instance->starts_at) ? null : $instance->starts_at->format("d-m-Y");
        $instance->formatted_ends_at = is_null($instance->ends_at) ? null : $instance->ends_at->format("d-m-Y");
        $instance->formatted_created_at = $instance->created_at->format("d-m-Y H:m:s");
        $instance->formatted_updated_at = $instance->updated_at->format("d-m-Y H:m:s");

        // View page's href
        $instance->view_href = route("projects.view", $instance->slug);

        // Determine if logged in user is a team member
        $instance->is_team_member = $this->isTeamMember($instance);
        $instance->has_outstanding_application = TeamMemberApplications::hasOutstandingApplication($instance);
        if ($instance->has_outstanding_application) $instance->outstanding_application = TeamMemberApplications::getOutstandingApplication($instance);
        $instance->num_outstanding_applications = TeamMemberApplications::numOutstandingApplications($instance);
        $instance->is_owner = $this->isOwner($instance);

        // Get relationship counts for view task page (sidebar)
        $instance->num_reviews = $this->getNumReviews($instance);
        $instance->num_comments = $this->getNumComments($instance);
        $instance->num_resources = count($instance->resources);
        $instance->num_tasks = count($instance->tasks);
        $instance->num_team_roles = count($instance->team_roles);
        $instance->num_team_members = count($instance->team_members);

        // Return the upgraded project
        return $instance;
    }

    public function findBySlug($slug)
    {
        foreach ($this->getAll() as $project)
        {
            if ($project->slug == $slug)
            {
                return $project;
            }
        }

        return false;
    }

    public function findPreloadedBySlug($slug)
    {
        foreach ($this->getAllPreloaded() as $project)
        {
            if ($project->slug == $slug)
            {
                return $project;
            }
        }

        return false;
    }

    public function createFromRequest(CreateProjectRequest $request)
    {
        // Parse dates to Carbon objects
        $starts_at = Dates::parse($request->starts_at, "-")->format("Y-m-d");
        $ends_at = $request->has("ends_at") ? Dates::parse($request->ends_at, "-")->format("Y-m-d") : null;

        // Find or create the project's category by it's label
        $category = ProjectCategories::findOrCreateByLabel($request->project_category);

        // Find or create the project's phase
        $phase_id = null;
        if ($request->has("project_phase") && $request->project_phase !== "")
        {
            $phase_id = ProjectPhases::findOrCreateByLabel($request->project_phase)->id;
        }

        // Compose all of the data we know will be part of the new project
        $data = [
            "author_id" => Auth::user()->id,
            "project_status_id" => $request->project_status_id,
            "project_category_id" => $category->id,
            "project_phase_id" => $phase_id,
            "title" => $request->title,
            "slogan" => [
                "nl" => $request->slogan_nl,
                "en" => $request->slogan_en,
            ],
            "description" => [
                "nl" => $request->description_nl,
                "en" => $request->description_en,
            ],
            "starts_at" => $starts_at,
            "ends_at" => $ends_at,
            "has_tasks" => $request->has_tasks == "true" ? true : false,
            "has_deadline" => $request->has_deadline == "true" ? true : false,
            "project_code" => $request->project_code,
            "budget" => $request->budget,
        ];

        // Process ownership relationships
        if ($request->has("ministry_id") && intval($request->ministry_id) > 0)
        {
            $data["ministry_id"] = $request->ministry_id;
            if ($request->has("organization_id") && intval($request->organization_id) > 0)
            {
                $data["organization_id"] = $request->organization_id;
                if ($request->has("department") && $request->department !== "" && !is_null($request->department)) 
                {
                    $organization = Organizations::find($request->organization_id);
                    if ($organization)
                    {
                        $department = OrganizationDepartments::findOrCreateByName($organization, $request->department);
                        if ($department)
                        {
                            $data["organization_department_id"] = $department->id;
                        }
                    }
                }
            }
        }

        // Process optional header image
        if ($request->hasFile("header_image"))
        {
            $data["header_image_url"] = Uploader::upload($request->file("header_image"), "images/projects/header");
        }

        // Create the project
        $project = Project::create($data);

        // Process project's resources
        $this->processProjectResources($project, $request->resources);

        // Process project's team roles
        $this->processTeamRoles($project, $request->team_roles);

        // Process project's tags
        $this->processProjectTags($project, $request->tags);

        // Fire off events
        event(new ProjectCreated($project));
        event(new UserCreatedProject(auth()->user(), $project));

        // Return the created project
        return $project;
    }
    
    public function updateFromRequest(Project $project, UpdateProjectRequest $request)
    {
        // Grab the current status of the project before updating it
        // so we can make a comparison later on and see if it was opened or closed
        $currentStatus = $project->status;

        // Parse dates
        $starts_at = Dates::parse($request->starts_at, "-")->format("Y-m-d");
        $ends_at = $request->has("ends_at") ? Dates::parse($request->ends_at, "-")->format("Y-m-d") : null;

        // Find or create the project's category by it's label
        $category = ProjectCategories::findOrCreateByLabel($request->project_category);

        // Find or create the project's phase
        $phase_id = null;
        if ($request->has("project_phase") && $request->project_phase !== "")
        {
            $phase_id = ProjectPhases::findOrCreateByLabel($request->project_phase)->id;
        }

        // Process ownership relationships
        $ministry = Ministries::find($request->ministry_id);
        if ($ministry)
        {
            $project->ministry_id = $ministry->id;
            $organization = Organizations::find($request->organization_id);
            if ($organization)
            {
                $project->organization_id = $organization->id;
                if ($request->department === "" || is_null($request->department))
                {
                    $project->organization_department_id = null;
                }
                else
                {
                    $department = OrganizationDepartments::findOrCreateByName($organization, $request->department);
                    $project->organization_department_id = $department->id;
                }
            }
            else
            {
                $project->organization_id = null;
                $project->organization_department_id = null;
            }
        }
        else
        {
            $project->ministry_id = null;
            $project->organization_id = null;
            $project->organization_department_id = null;
        }

        // Update project
        $project->project_status_id = $request->project_status_id;
        $project->project_category_id = $category->id;
        $project->project_phase_id = $phase_id;
        $project->title = $request->title;
        $project->slogan = [
            "en" => $request->slogan_en,
            "nl" => $request->slogan_nl,
        ];
        $project->description = [
            "en" => $request->description_en,
            "nl" => $request->description_nl,
        ];
        $project->starts_at = $starts_at;
        $project->ends_at = $ends_at;
        $project->project_code = $request->project_code;
        $project->budget = $request->budget;

        // Upload header image
        if ($request->hasFile("header_image"))
        {
            $project->header_image_url = Uploader::upload($request->file("header_image"), "images/projects/header");
        }

        // Save changes
        $project->save();

        // Process project resources
        $this->processProjectResources($project, $request->resources);

        // Process project tags
        $this->processProjectTags($project, $request->tags);
        
        // Fire off events
        event(new ProjectUpdated($project));
        event(new UserUpdatedProject(auth()->user(), $project));
        if ($currentStatus->name == "open" && $project->status->name == "closed")
        {
            event(new ProjectCompleted($project));
        }

        // Return the updated project
        return $project;
    }

    private function processProjectTags(Project $project, $encodedTags)
    {
        $tagNames = json_decode($encodedTags);
        if (is_array($tagNames) && count($tagNames))
        {
            $tag_ids = [];

            foreach ($tagNames as $name)
            {
                $tag = Tags::findOrCreateByName($name);
                if ($tag)
                {
                    $tag_ids[] = $tag->id;
                }
            }

            $project->tags()->sync($tag_ids);
        }
    }

    private function processProjectResources(Project $project, $encodedResources)
    {
        // TODO: make this function smarter
        $project->resources()->delete();

        if (!is_null($encodedResources) and $encodedResources !== "" and $encodedResources !== "[]")
        {
            $resources = json_decode($encodedResources);
            if (is_array($resources) and count($resources))
            {
                foreach ($resources as $resource_id)
                {
                    $resource = ProjectResources::find($resource_id);
                    if ($resource)
                    {
                        $resource->project_id = $project->id;
                        $resource->save();
                    }
                }
            }
        }

        return $project;
    }

    private function processTeamRoles(Project $project, $encodedTeamRoles)
    {
        // TODO: make this function smarter; only delete those members that have actually been removed
        // so that existing team members don't lose their data. For MVP; delete that shiiiit.
        $project->teamRoles()->delete();

        if (!is_null($encodedTeamRoles) and $encodedTeamRoles !== "" and $encodedTeamRoles !== "[]")
        {
            $teamRoles = json_decode($encodedTeamRoles);
            if (is_array($teamRoles) and count($teamRoles))
            {
                foreach ($teamRoles as $teamRole)
                {
                    $skill_ids = [];
                    if (count($teamRole->skills))
                    {
                        foreach ($teamRole->skills as $skillName)
                        {
                            $skill = Skills::findOrCreateByName($skillName);
                            if ($skill) $skill_ids[] = $skill->id;
                        }
                    }

                    $tm = TeamRole::create([
                        "project_id" => $project->id,
                        "name" => $teamRole->name,
                        "description" => $teamRole->description
                    ]);
                    
                    if (count($skill_ids)) $tm->skills()->attach($skill_ids);
                }
            }
        }

        return $project;
    }

    private function createDefaultTaskColumnsForProject(Project $project)
    {
        $defaultColumns = ["To do", "In progress", "Completed"];
        for ($i = 0; $i < count($defaultColumns); $i++) {
            TaskColumn::create([
                "project_id" => $project->id,
                "order" => $i,
                "title" => $defaultColumns[$i],
            ]);
        }
    }

    public function processTeamApplication(Project $project, ApplyForTeamRoleRequest $request)
    {
        $user = Users::current();

        return TeamMemberApplication::create([
            "project_id" => $project->id,
            "user_id" => $user->id,
            "team_role_id" => $request->team_role_id,
            "motivation" => $request->motivation
        ]);
    }

    public function getResources(Project $project)
    {
        $out = ProjectResources::getAllPreloadedForProject($project);
        return collect($out);
    }

    public function getAuthor(Project $project)
    {
        return Users::findAuthorForProject($project);
    }

    public function getTeamMemberApplications(Project $project)
    {
        $applications = collect(TeamMemberApplications::getAllForProject($project));
        
        $applications->map(function($application) use ($project) {
            $application->view_href = route("projects.team.applications.view", ["slug" => $project->slug, "uuid" => $application->uuid]);
            return $application;
        });

        return $applications;
    }

    public function getMyTeamMemberApplications(Project $project)
    {
        $out = [];

        $user = Users::current();
        
        $applications = TeamMemberApplications::getAllForProject($project);
        foreach ($applications as $application)
        {
            if ($application->user_id == $user->id) $out[] = $application;
        }

        return collect($out);
    }

    public function getOutstandingRoles(Project $project)
    {
        $out = [];

        foreach ($project->teamRoles as $teamRole)
        {
            if ($teamRole->teamMembers->count() and $teamRole->teamMembers->count() == $teamRole->positions)
            {
                // dd($teamRole, $teamRole->teamMembers, $teamRole->teamMembers->count() == $teamRole->positions);
                continue;
            }
            
            $out[] = $teamRole;
        }

        return collect($out);
    }

    public function isTeamMember(Project $project, User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $members = TeamMembers::getAllForProject($project);

        if (count($members))
        {
            foreach ($members as $member)
            {
                if ($member->user_id == $user->id)
                {
                    return true;
                }
            }
        }

        return false;
    }

    public function isOwner(Project $project)
    {
        $user = auth()->user();

        return $user->is_admin or $project->author_id == $user->id;
    }

    public function findTeamMember(Project $project, User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        if ($project->teamMembers->count())
        {
            foreach ($project->teamMembers as $teamMember)
            {
                if ($teamMember->user_id == $user->id)
                {
                    return TeamMembers::preload($teamMember);
                }
            }
        }

        return false;
    }

    public function hasOutstandingTeamApplication(User $user, Project $project)
    {
        $applications = TeamMemberApplications::getAllForProject($project);

        if (count($applications))
        {
            foreach($applications as $application)
            {
                if ($application->user_id == $user->id)
                {
                    return true;
                }
            }
        }

        return false;
    }

    public function getAllForUser(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $out = [];

        foreach ($this->getAllPreloaded() as $project)
        {
            if ($project->author_id == $user->id)
            {
                $out[] = $project;
            }
            else
            {
                $isTeamMember = false;
                foreach ($project->teamMembers as $teamMember)
                {
                    if ($teamMember->user_id == $user->id)
                    {
                        $isTeamMember = true;
                        break;
                    }
                }

                if ($isTeamMember) $out[] = $project;
            }
        }

        return collect($out);
    }

    public function getAllOngoingForUser(User $user = null)
    {
        $projects = $this->getAllForUser($user)
            ->map(function($project) {
                return $project->status->name !== "closed" ? $project : false;
            })
            ->reject(function($project) {
                return $project === false;
            });

        return $projects;
    }

    public function getNumComments(Project $project)
    {
        $comments = Comments::getAllForProject($project);

        return $comments->count();
    }

    public function getNumReviews(Project $project, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();

        $reviews = Reviews::getAllForProject($project);

        if ($user->is_admin || $project->author_id == $user->id)
        {
            return $reviews->count();
        }
        else
        {
            $out = 0;

            foreach ($reviews as $review)
            {
                if ($review->public)
                {
                    $out += 1;
                }
            }

            return $out;
        }
    }
}