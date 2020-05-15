<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function getLogout()
    {
        Auth::logout();
        
        flash(__("auth.logout_cya_later"))->success();
        return redirect()->route("home");
    }
}