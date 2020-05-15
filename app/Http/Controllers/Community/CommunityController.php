<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;

class CommunityController extends Controller
{
    public function getOverview()
    {
        return view("pages.community.overview", []);
    }
}