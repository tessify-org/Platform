<?php

namespace App\Http\Controllers\System;

use Search;
use App\Http\Controllers\Controller;
use App\Http\Requests\Search\SearchRequest;

class SearchController extends Controller
{
    public function getSearch()
    {
        return view("pages.system.search.search", [
            "query" => null,
        ]);
    }

    public function postSearch(SearchRequest $request)
    {
        return view("pages.system.search.search", [
            "query" => $request->search_query,
            "results" => Search::search($request->search_query),
        ]);
    }
}