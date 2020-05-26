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
            "strings" => collect([
                "search_placeholder" => __("search.search_placeholder"),
                "no_results" => __("search.no_results"),
                "enter_query" => __("search.enter_query"),
                "user_type" => __("search.user_type"),
                "task_type" => __("search.task_type"),
                "project_type" => __("search.project_type"),
                "ministry_type" => __("search.ministry_type"),
                "organization_type" => __("search.organization_type"),
                "results_found" => __("search.results_found"),
                "result_found" => __("search.result_found"),
            ]),
        ]);
    }

    public function postSearch(SearchRequest $request)
    {
        return view("pages.system.search.search", [
            "query" => $request->search_query,
            "results" => Search::search($request->search_query),
            "strings" => collect([
                "search_placeholder" => __("search.search_placeholder"),
                "no_results" => __("search.no_results"),
                "enter_query" => __("search.enter_query"),
                "user_type" => __("search.user_type"),
                "task_type" => __("search.task_type"),
                "project_type" => __("search.project_type"),
                "ministry_type" => __("search.ministry_type"),
                "organization_type" => __("search.organization_type"),
                "results_found" => __("search.results_found"),
                "result_found" => __("search.result_found"),
            ]),
        ]);
    }
}