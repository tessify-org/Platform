<?php

namespace App\Http\Controllers\Projects;

use Tasks;
use Projects;
use App\Http\Controllers\Controller;

class ProjectTaskController extends Controller
{
    public function getOverview($slug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        return view("pages.projects.tasks.overview", [
            "project" => $project,
            "tasks" => Tasks::getAllForProject($project),
            "strings" => collect([
                "title" => __("projects.tasks_title"),
                "no_records" => __("projects.tasks_no_records"),
                "create" => __("projects.tasks_create"),
            ]),
        ]);
    }
}