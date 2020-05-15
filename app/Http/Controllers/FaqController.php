<?php

namespace App\Http\Controllers;

class FaqController extends Controller
{
    public function getOverview()
    {
        return view("pages.faq.overview");
    }
}