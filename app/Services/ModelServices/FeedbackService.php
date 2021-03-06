<?php

namespace App\Services\ModelServices;

use Auth;
use App\Models\Feedback;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\System\FeedbackRequest;
use App\Jobs\System\Feedback\SendFeedbackEmail;
use App\Jobs\System\Feedback\SendGebruikerspanelFeedbackEmail;

class FeedbackService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Feedback";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function processFeedbackRequest(FeedbackRequest $request)
    {
        $feedback = Feedback::create([
            "user_id" => auth()->user()->id,
            "target" => request("target"),
            "page_url" => request("page_url"),
            "severity" => request("severity", 1),
            "subject" => request("subject"),
            "message" => request("description"),
        ]); 

        if (request("target") == "gebruikerspanel" && config("platform.feedback.gebruikerspanel.emails_enabled"))
        {
            $emails = collect(config("platform.feedback.gebruikerspanel.emails"));
            foreach ($emails as $email)
            {
                SendGebruikerspanelFeedbackEmail::dispatch($email, $feedback)->onQueue("emails");
            }
        }

        if (config("platform.feedback.general.emails_enabled"))
        {
            $emails = config("platform.feedback.general.emails");
            foreach ($emails as $email)
            {
                SendFeedbackEmail::dispatch($email, $feedback)->onQueue("emails");
            }
        }

        return $feedback;
    }
}