<?php

namespace App\Services\ModelServices;

use Auth;
use App\Models\TaskProgressReport;
use App\Models\TaskProgressReportReview;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Tasks\ReviewProgressReportRequest;

class TaskProgressReportReviewService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\TaskProgressReportReview";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function getAllForReport(TaskProgressReport $report)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $review)
        {
            if ($review->task_progress_report_id == $report->id)
            {
                $out[] = $review;
            }
        }

        return $out;
    }

    public function createFromRequest(TaskProgressReport $report, ReviewProgressReportRequest $request)
    {
        return TaskProgressReportReview::create([
            "task_progress_report_id" => $report->id,
            "user_id" => Auth::user()->id,
            "message" => $request->message
        ]);
    }

    public function markAsRead(TaskProgressReportReview $review)
    {
        $review->read_by_assigned_user = true;
        $review->save();

        return $review;
    }
}