<?php

namespace App\Http\Controllers\System;

use Tags;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function getOverview()
    {
        // Grab all available tags
        $tags = Tags::getAll();

        // Render the tag overview page
        return view("pages.system.tags.overview", [
            "tags" => $tags,
        ]);
    }

    public function getView($slug)
    {
        // Grab the tag we want to view
        $tag = Tags::findBySlug($slug);
        if (!$tag)
        {
            flash(__("tags.not_found"))->error();
            return redirect()->route("search");
        }

        // Render the view tag page
        return view("pages.system.tags.view", [
            "tag" => $tag,
        ]);
    }
}