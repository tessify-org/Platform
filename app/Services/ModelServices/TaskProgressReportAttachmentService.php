<?php

namespace App\Services\ModelServices;

use App\Models\TaskProgressReport;
use App\Models\TaskProgressReportAttachment;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class TaskProgressReportAttachmentService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\TaskProgressReportAttachment";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function getAllForReport(TaskProgressReport $report)
    {
        $out = [];

        foreach ($this->getAll() as $attachment)
        {
            if ($attachment->task_progress_report_id == $report->id)
            {
                $out[] = $attachment;
            }
        }

        return $out;
    }
}