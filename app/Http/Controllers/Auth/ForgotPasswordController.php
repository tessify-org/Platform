<?php

namespace App\Http\Controllers\Auth;

use Users;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    public function getForgotPassword()
    {
        return view("pages.auth.forgot-password");
    }

    public function postForgotPassword(ForgotPasswordRequest $request)
    {
        Users::sendRecoverAccountEmail($request->email);

        return view("pages.auth.forgot-password-email-sent", [
            "email" => $request->email
        ]);
    }
}