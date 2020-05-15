<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    public function getDontUseInternetExplorer()
    {
        return view("pages.static.dont-use-ie");
    }
}