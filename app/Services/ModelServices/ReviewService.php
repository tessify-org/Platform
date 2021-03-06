<?php

namespace App\Services\ModelServices;

use Users;
use Tasks;
use Projects;
use ReviewRequests;
use App\Models\User;
use App\Models\Task;
use App\Models\Review;
use App\Models\Project;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Reviews\CreateReviewRequest;
use App\Http\Requests\Reviews\UpdateReviewRequest;

class ReviewService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Review";
    }
    
    public function preload($instance)
    {
        $instance->user = Users::findPreloaded($instance->user_id);
        $instance->view_href = route("reviews.view", $instance->uuid);
        $instance->formatted_date = $instance->created_at->format("d-m-Y H:m:s");
        
        switch ($instance->reviewable_type)
        {
            case "App\\Models\\User":
                $instance->formatted_type = __("reviews.type_user");
                $instance->formatted_name = $instance->reviewable->formatted_name;
            break;
            case "App\\Models\\Task":
                $instance->formatted_type = __("reviews.type_task");
                $instance->formatted_name = $instance->reviewable->title;
            break;
            case "App\\Models\\Project":
                $instance->formatted_type = __("reviews.type_project");
                $instance->formatted_name = $instance->reviewable->title;
            break;
        }

        return $instance;
    }

    public function findByUuid($uuid)
    {
        foreach ($this->getAll() as $review)
        {
            if ($review->uuid == $uuid)
            {
                return $review;
            }
        }

        return false;
    }

    public function findPreloadedByUuid($uuid)
    {
        foreach ($this->getAllPreloaded() as $review)
        {
            if ($review->uuid == $uuid)
            {
                return $review;
            }
        }

        return false;
    }

    public function findTarget($type, $slug)
    {
        switch ($type)
        {
            case "user":
                return Users::findBySlug($slug);
            break;
            case "task":
                return Tasks::findBySlug($slug);
            break;
            case "project":
                return Projects::findBySlug($slug);
            break;
        }

        return false;
    }

    public function getMyReviews(User $user = null)
    {
        if (is_null($user)) $user = auth()->user();

        $out = [];

        foreach ($this->getAllPreloaded() as $review)
        {
            if ($review->user_id == $user->id)
            {
                $out[] = $review;
            }
        }

        return collect($out);
    }

    public function createFromRequest(CreateReviewRequest $request, $type, $slug)
    {
        // Grab the target we're reviewing
        $target = $this->findTarget($type, $slug);
        if (!$target)
        {
            flash(__("reviews.target_not_found"))->error();
            return redirect()->route("reviews");
        }

        // Complete any outstanding review request that matches the target
        ReviewRequests::completeOutstandingRequestFor($type, $slug);

        // Create and return the review
        return Review::create([
            "user_id" => auth()->user()->id,
            "reviewable_type" => get_class($target),
            "reviewable_id" => $target->id,
            "rating" => $request->rating,
            "message" => $request->message,
            "public" => $request->public === "true" ? true : false,
        ]);
    }

    public function updateFromRequest(UpdateReviewRequest $request, Review $review)
    {
        // Update the review
        $review->rating = $review->rating;
        $review->message = $review->message;
        $review->public = $request->public === "true" ? true : false;
        $review->save();

        // Return the review
        return $review;
    }

    public function getAllForTask(Task $task)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $review)
        {
            if ($review->reviewable_type == get_class($task) && $review->reviewable_id == $task->id)
            {
                $out[] = $review;
            }
        }
        
        return collect($out);
    }

    public function getAllForProject(Project $project)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $review)
        {
            if ($review->reviewable_type == get_class($project) && $review->reviewable_id == $project->id)
            {
                $out[] = $review;
            }
        }

        return collect($out);
    }

    public function getAllForUser(User $user)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $review)
        {
            if ($review->reviewable_type == get_class($user) && $review->reviewable_id == $user->id)
            {
                $out[] = $review;
            }
        }

        return collect($out);
    }
}