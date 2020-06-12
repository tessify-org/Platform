<?php

namespace App\Http\Controllers\System;

use Tags;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function getOverview()
    {
        return view("pages.system.tags.overview", [
            "tags" => Tags::getAllPreloaded(),
            "strings" => collect([
                "search" => __("tags.overview_search"),
                "no_records" => __("tags.overview_no_records"),
            ]),
        ]);
    }

    public function getView($slug)
    {
        $tag = Tags::findBySlug($slug);
        if (!$tag)
        {
            flash(__("tags.not_found"))->error();
            return redirect()->route("search");
        }
        
        return view("pages.system.tags.view", [
            "tag" => $tag,
        ]);
    }
}