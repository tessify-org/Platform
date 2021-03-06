<?php

namespace App\Http\Controllers\Tasks;

use Tasks;
use Reviews;

use App\Http\Controllers\Controller;

class TaskReviewController extends Controller
{
    public function getOverview($slug)
    {
        // Grab the task we want to view
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Render the task review overview page
        return view("pages.tasks.reviews.overview", [
            "task" => $task,
            "reviews" => Reviews::getAllForTask($task),
            "strings" => collect([
                "title" => __("tasks.reviews_title"),
                "no_records" => __("tasks.reviews_no_records"),
            ]),
        ]);
    }

    public function getView($slug, $uuid)
    {
        // Grab the task we want to view
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Grab the task review we want to view
        $review = Reviews::findByUuid($uuid);
        if (!$review)
        {
            flash(__("tasks.review_not_found"))->error();
            return redirect()->route("tasks.view", $task->slug);
        }
        
        // Render the task review page
        return view("pages.tasks.reviews.view", [
            "task" => $task,
            "review" => $review,
        ]);
    }
}