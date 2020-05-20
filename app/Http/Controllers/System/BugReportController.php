<?php

namespace App\Http\Controllers\System;

use BugReports;
use App\Http\Controllers\Controller;
use App\Http\Requests\BugReports\SubmitBugReportRequest;

class BugReportController extends Controller
{
    public function postSubmitReport(SubmitBugReportRequest $request)
    {
        $report = BugReports::createFromRequest($request);
        return view("pages.bug-reports.thank-you", ["report" => $report]);
    }
}