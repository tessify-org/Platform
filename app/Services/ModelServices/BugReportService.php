<?php

namespace App\Services\ModelServices;

use Auth;
use App\Models\BugReport;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\BugReports\SubmitBugReportRequest;

class BugReportService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\BugReport";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function createFromRequest(SubmitBugReportRequest $request)
    {
        return BugReport::create([
            "user_id" => Auth::check() ? Auth::user()->id : null,
            "url" => $request->url,
            "severity" => $request->severity,
            "report" => $request->report,
        ]);
    }
}