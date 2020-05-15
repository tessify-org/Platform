<?php

namespace App\Http\Controllers\System;

use Notifications;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function getOverview()
    {
        Notifications::markAllAsRead();
        
        return view("pages.system.notifications.overview", [
            "notifications" => Notifications::get(),
        ]);
    }

    public function getClear()
    {
        Notifications::clear();
        
        flash(__("pages.system.notifications.cleared"))->success();
        return redirect()->route("notifications");
    }
}