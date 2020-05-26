<?php

namespace App\Http\Controllers\Profiles;

use Users;
use Memberlist;
use Ministries;
use Organizations;
use App\Http\Controllers\Controller;

class MemberlistController extends Controller
{
    public function getMemberlist()
    {
        return view("pages.profiles.memberlist", [
            "users" => Users::getAllPreloaded(),
            "ministries" => Ministries::getAll(),
            "organizations" => Organizations::getAll(),
            "strings" => collect([
                "name" => __("profiles.memberlist_name"),
                "reputation" => __("profiles.memberlist_reputation"),
                "no_records" => __("profiles.memberlist_no_users"),
                "points" => __("profiles.memberlist_points"),
                "search" => __("profiles.memberlist_search"),
                "ministry" => __("profiles.memberlist_ministry"),
                "organization" => __("profiles.memberlist_organization"),
            ]),
        ]);
    }
}