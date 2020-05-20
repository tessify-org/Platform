<?php

namespace App\Http\Controllers\System;

use Feedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\FeedbackRequest;

class FeedbackController extends Controller
{
    public function postFeedback(FeedbackRequest $request)
    {
        // Process feedback request
        $report = Feedback::processFeedbackRequest($request);

        // Render the thank you page
        return view("pages.feedback.thank-you", ["report" => $report]);
    }
}