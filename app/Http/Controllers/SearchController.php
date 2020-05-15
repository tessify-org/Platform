<?php

namespace App\Http\Controllers;

use App\Http\Requests\Search\SearchRequest;

class SearchController extends Controller
{
    public function getSearch()
    {
        return view("pages.search.search-results", []);
    }

    public function postSearch(SearchRequest $request)
    {
        return view("pages.search.search-results", []);
    }
}