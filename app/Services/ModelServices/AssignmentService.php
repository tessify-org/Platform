<?php

namespace App\Services\ModelServices;

use Auth;
use Dates;
use AssignmentTypes;
use Organizations;
use OrganizationTypes;
use OrganizationLocations;
use OrganizationDepartments;
use App\Models\User;
use App\Models\Assignment;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Assignments\CreateAssignmentRequest;
use App\Http\Requests\Assignments\UpdateAssignmentRequest;
use App\Http\Requests\Api\Assignments\CreateAssignmentRequest as ApiCreateRequest;
use App\Http\Requests\Api\Assignments\UpdateAssignmentRequest as ApiUpdateRequest;

class AssignmentService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Assignment";
    }
    
    public function preload($instance)
    {
        // Preload relationships
        $instance->type = AssignmentTypes::find($instance->assignment_type_id);
        $instance->organization = Organizations::findPreloaded($instance->organization_id);
        $instance->department = OrganizationDepartments::find($instance->organization_department_id);
        $instance->location = OrganizationLocations::find($instance->organization_location_id);

        // Clean up the dates
        $instance->formatted_start_date = $instance->start_date->format("d-m-Y");
        $instance->formatted_end_date = !is_null($instance->end_date) ? $instance->end_date->format("d-m-Y") : null;
        
        return $instance;
    }

    public function findAllPreloadedForUser(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $out = [];

        foreach ($this->getAllPreloaded() as $assignment)
        {
            if ($assignment->user_id == $user->id)
            {
                $out[] = $assignment;
            }
        }

        return $out;
    }

    public function createFromApiRequest(ApiCreateRequest $request)
    {
        dd($request->all());

        // Grab current user
        $user = Auth::user();

        // Determine the order for this assignment
        $order = $user->assignments->count();

        // Determine if this is the current assignment of the user
        $current = $request->current == "true" ? true : false;
        if ($current and $user->assignments->count()) $this->deactiveAllForUser($user);

        // Retrieve or create the organization
        $organization = Organizations::findOrCreateByName($request->organization);

        // Retrieve or create the organization's department
        $department = OrganizationDepartments::findOrCreateByName($organization, $request->department);

        // Retrieve or create the organization's location
        if ($request->organization_location != null && $request->organization_location != "") {
            $location = OrganizationLocations::findOrCreateByAddress($organization, $request->organization_location);
        } else {
            $location = null;
        }

        // Parse the dates
        $start_date = Dates::parse($request->start_date, "-")->format("Y-m-d");
        $end_date = $request->end_date == "null" ? null : Dates::parse($request->end_date, "-")->format("Y-m-d");
        
        // Create and return the assignment
        return $this->preload(Assignment::create([
            "user_id" => $user->id,
            "assignment_type_id" => intval($request->assignment_type_id),
            "organization_id" => $organization->id,
            "organization_department_id" => $department->id,
            "organization_location_id" => is_null($location) ? null : $location->id,
            "title" => $request->title,
            "description" => $request->description,
            "order" => $order,
            "current" => $current,
            "start_date" => $start_date,
            "end_date" => $end_date
        ]));
    }

    public function updateFromApiRequest(ApiUpdateRequest $request)
    {
        // Grab current user
        $user = Auth::user();

        // Grab the assignment we're updating
        $assignment = $this->find($request->assignment_id);

        // Determine if this assignment is now the current assignment
        // If so, deactive the current flag on all other assignments of this user
        $current = $request->current == "true" ? true : false;
        if ($current and $user->assignments->count() > 1) $this->deactiveAllForUser($user);
        
        // Parse the dates
        $start_date = Dates::parse($request->start_date, "-")->format("Y-m-d");
        $end_date = $request->end_date == "null" ? null : Dates::parse($request->end_date, "-")->format("Y-m-d");

        // Retrieve or create the organization
        $organization = Organizations::findOrCreateByName($request->organization);

        // Retrieve or create the organization's department
        $department = OrganizationDepartments::findOrCreateByName($organization, $request->department);

        // Retrieve or create the organization's location
        if ($request->organization_location != null && $request->organization_location != "") {
            $location = OrganizationLocations::findOrCreateByAddress($organization, $request->organization_location);
        } else {
            $location = null;
        }

        // Update assignment
        $assignment->assignment_type_id = intval($request->assignment_type_id);
        $assignment->organization_id = $organization->id;
        $assignment->organization_department_id = $department->id;
        $assignment->organization_location_id = is_null($location) ? null : $location->id;
        $assignment->title = $request->title;
        $assignment->description = $request->description;
        $assignment->current = $current;
        $assignment->start_date = $start_date;
        $assignment->end_date = $end_date;
        $assignment->save();

        // Return updated & preloaded assignment
        return $this->preload($assignment);
    }

    public function deactiveAllForUser(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        foreach ($user->assignments as $assignment)
        {
            $assignment->current = false;
            $assignment->save();
        }
    }
}