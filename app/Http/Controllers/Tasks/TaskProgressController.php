<?php

namespace App\Http\Controllers\Tasks;

use Tasks;
use TaskProgressReports;
use TaskProgressReportReviews;

use App\Events\Tasks\TaskProgressReported;
use App\Http\Requests\Tasks\ReportProgressRequest;
use App\Http\Requests\Tasks\ReviewProgressReportRequest;

use App\Http\Controllers\Controller;

class TaskProgressController extends Controller
{
    public function getReportProgress($slug)
    {
        // Grab the task we want to report progress on
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Render the report progress page
        return view("pages.tasks.report-progress", [
            "task" => $task,
            "oldInput" => collect([
                "message" => old("message"),
                "hours" => old("hours"),
                "completed" => old("completed"),
            ]),
            "strings" => collect([
                "back" => __("tasks.report_progress_back"),
                "submit" => __("tasks.report_progress_submit"),
                "message" => __("tasks.report_progress_message"),
                "message_placeholder" => __("tasks.report_progress_message_placeholder"),
                "hours" => __("tasks.report_progress_hours"),
                "attachment" => __("tasks.report_progress_attachment"),
                "completed" => __("tasks.report_progress_completed"),
            ]),
        ]);
    }

    public function postReportProgress(ReportProgressRequest $request, $slug)
    {
        // Grab the task we want to report progress on
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Create the progress report
        $report = TaskProgressReports::createFromRequest($task, $request);

        // Fire events
        event(new TaskProgressReported($task));

        // Flash message & redirect to the view progress report page
        flash(__("tasks.report_progress_success"))->success();
        return redirect()->route("tasks.progress-report", ["slug" => $task->slug, "uuid" => $report->uuid]);
    }

    public function getOverview($slug)
    {
        // Grab the task to which the report belongs
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Render the view progress report page
        return view("pages.tasks.progress-reports.overview", [
            "task" => $task,
            "reports" => collect(TaskProgressReports::getAllForTask($task)),
            "strings" => collect([
                "no_records" => __("tasks.progress_reports_no_records"),
                "hours" => __("general.hour"),
            ]),
        ]);
    }

    public function getView($slug, $uuid)
    {
        // Grab the task to which the report belongs
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Grab the progress report
        $report = TaskProgressReports::findPreloadedByUuid($uuid);
        if (!$report)
        {
            flash(__("projects.progress_report_not_found"))->error();
            return redirect()->route("tasks.view", ["slug" => $slug]);
        }

        // If this task is assigned to the current user
        if ($task->is_assigned)
        {
            // Mark the reviews this progress report may contain as read
            TaskProgressReports::markReviewsAsRead($report);
        }

        // Render the view progress report page
        return view("pages.tasks.progress-reports.view", [
            "task" => $task,
            "report" => $report,
        ]);
    }

    public function getReviewProgressReport($slug, $uuid)
    {
        // Grab the task the progress report we want to review belongs to
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Grab the progress report we want to review
        $report = TaskProgressReports::findByUuid($uuid);
        if (!$report)
        {
            flash(__("projects.progress_report_not_found"))->error();
            return redirect()->route("tasks.view", ["slug" => $slug]);
        }

        // Render the review progress report page
        return view("pages.tasks.review-progress-report", [
            "task" => $task,
            "report" => $report,
            "oldInput" => collect([
                "message" => old("message"),
                "completed" => old("completed"),
            ])
        ]);
    }

    public function postReviewProgressReport(ReviewProgressReportRequest $request, $slug, $uuid)
    {
        // Grab the task the progress report we want to review belongs to
        $task = Tasks::findPreloadedBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Grab the progress report we want to review
        $report = TaskProgressReports::findByUuid($uuid);
        if (!$report)
        {
            flash(__("projects.progress_report_not_found"))->error();
            return redirect()->route("tasks.view", ["slug" => $slug]);
        }

        // Create the progress report review
        $review = TaskProgressReportReviews::createFromRequest($report, $request);

        // Flash message & redirect to the view progress report page
        flash(__("tasks.reviewed_progress_report"))->success();
        return redirect()->route("tasks.progress-report", ["slug" => $slug, "uuid" => $report->uuid]);
    }

    public function getComplete($slug)
    {
        // Grab the task we want to complete
        $task = Tasks::findBySlug($slug);
        if (!$task)
        {
            flash(__("projects.task_not_found"))->error();
            return redirect()->route("tasks");
        }

        // Complete the task
        Tasks::complete($task);

        // Flash message & redirect to the view task page
        flash(__("tasks.completed"))->success();
        return redirect()->route("tasks.view", ["slug" => $task->slug]);
    }
}