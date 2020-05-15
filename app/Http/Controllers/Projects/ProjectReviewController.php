<?php

namespace App\Http\Controllers\Projects;

use Reviews;
use Projects;

use App\Http\Controllers\Controller;

class ProjectReviewController extends Controller
{
    public function getOverview($slug)
    {
        // Grab the project we want to complete
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Render the project review overview page
        return view("pages.projects.reviews.overview", [
            "project" => $project,
            "reviews" => Reviews::getAllForProject($project),
            "strings" => collect([
                "no_records" => __("projects.reviews_no_records"),
            ]),
        ]);
    }
}