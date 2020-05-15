<?php

namespace App\Http\Controllers\Api;

use Newsletters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Newsletter\SignupForNewsletterRequest;

class NewsletterController extends Controller
{
    public function postSignup(SignupForNewsletterRequest $request)
    {
        Newsletters::createFromRequest($request);
        return response()->json(["status" => "success"]);
    }   
}