<?php

namespace App\Http\Controllers;

class StaticPageController extends Controller
{
    public function getStaticPage()
    {
        return view("pages.static.page");
    }

    public function getPress()
    {
        return view("pages.static.press");
    }

    public function getPartners()
    {
        return view("pages.static.partners");
    }

    public function getAbout()
    {
        return view("pages.static.about");
    }

    public function getDoMore()
    {
        return view("pages.static.do-more");
    }
}