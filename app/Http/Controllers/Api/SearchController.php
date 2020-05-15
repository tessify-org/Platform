<?php

namespace App\Http\Controllers\Api;

use Search;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Search\SearchRequest;

class SearchController extends Controller
{
    public function postSearch(SearchRequest $request)
    {
        return response()->json([
            "status" => "success",
            "results" => Search::search($request->search_query),
        ]);
    }
}