<?php

namespace App\Services\ModelServices;

use Auth;
use Users;
use Uploader;
use TaskProgressReportReviews;
use TaskProgressReportAttachments;
use App\Models\Task;
use App\Models\TaskProgressReport;
use App\Models\TaskProgressReportAttachment;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Tasks\ReportProgressRequest;
use App\Http\Requests\Tasks\UpdateProgressReportRequest;

class TaskProgressReportService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\TaskProgressReport";
    }
    
    public function preload($instance)
    {
        $instance->user = Users::findPreloaded($instance->user_id);
        $instance->reviews = TaskProgressReportReviews::getAllForReport($instance);
        $instance->attachments = TaskProgressReportAttachments::getAllForReport($instance);

        $instance->has_unread_reviews = $this->hasUnreadReviews($instance);
        $instance->has_been_reviewed = $this->hasBeenReviewed($instance);
        
        $instance->view_href = route("tasks.progress-report", ["slug" => $instance->task->slug, "uuid" => $instance->uuid]);
        $instance->formatted_date = $instance->created_at->format("d-m-Y H:m:s");

        return $instance;
    }

    public function findByUuid($uuid)
    {
        foreach ($this->getAll() as $report)
        {
            if ($report->uuid == $uuid)
            {
                return $report;
            }
        }

        return false;
    }

    public function findPreloadedByUuid($uuid)
    {
        foreach ($this->getAllPreloaded() as $report)
        {
            if ($report->uuid == $uuid)
            {
                return $report;
            }
        }

        return false;
    }

    public function getAllForTask(Task $task)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $report)
        {
            if ($report->task_id == $task->id)
            {
                $out[] = $report;
            }
        }

        return $out;
    }

    // Outstanding = has not been reviewed by owner yet
    public function getAllOutstandingForTask(Task $task)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $report)
        {
            if ($report->task_id == $task->id)
            {
                if (count($report->reviews) == 0)
                {
                    $out[] = $report;
                }
                else
                {
                    $has_unread_reviews = false;
                    foreach ($report->reviews as $review)
                    {
                        if (!$review->read_by_assigned_user)
                        {
                            $has_unread_reviews = true;
                            break;
                        }
                    }
                    if ($has_unread_reviews) $out[] = $report;
                }
            }
        }

        return $out;
    }

    public function hasUnreadReviews(TaskProgressReport $report)
    {
        foreach ($report->reviews as $review)
        {
            if (!$review->read_by_assigned_user)
            {
                return true;
            }
        }

        return false;
    }

    public function createFromRequest(Task $task, ReportProgressRequest $request)
    {
        $report = TaskProgressReport::create([
            "user_id" => Auth::user()->id,
            "task_id" => $task->id,
            "message" => $request->message,
            "hours" => $request->hours,
            "completed" => $request->completed == "true" ? true : false,
        ]);

        if ($request->hasFile("attachment"))
        {
            $attachment = TaskProgressReportAttachment::create([
                "task_progress_report_id" => $report->id,
                "file_url" => Uploader::upload($request->attachment, "files/task_progress_reports"),
            ]);
        }

        return $report;
    }

    public function updateFromRequest(TaskProgressReport $report, UpdateProgressReportRequest $request)
    {
        $report->message = $request->message;
        $report->hours = $request->hours;
        $report->completed = $request->completed == "true" ? true : false;
        $report->save();

        return $report;
    }

    public function hasBeenReviewed(TaskProgressReport $report)
    {
        $reviews = TaskProgressReportReviews::getAllForReport($report);
        return count($reviews) > 0;
    }

    public function markReviewsAsRead(TaskProgressReport $report)
    {
        foreach ($report->reviews as $review)
        {
            TaskProgressReportReviews::markAsRead($review);
        }
    }
}