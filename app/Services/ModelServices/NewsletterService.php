<?php

namespace App\Services\ModelServices;

use App\Models\NewsletterSignup;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Api\Newsletter\SignupForNewsletterRequest;

class NewsletterService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\NewsletterSignup";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function createFromRequest(SignupForNewsletterRequest $request)
    {
        return NewsletterSignup::create([
            "user_id" => auth()->check() ? auth()->user()->id : null,
            "email" => $request->email,
        ]);
    }
}