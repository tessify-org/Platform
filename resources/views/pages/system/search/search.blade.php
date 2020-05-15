@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("search") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title -->
            <h1 class="page-title centered">@lang("search.title")</h1>
        
            <!-- Feedback -->
            @include("partials.feedback")
            
            <!-- Search form -->
            <!-- <form action="{{ route('search.post') }}" method="post">
                @csrf
                <v-text-field name="search_query" solo></v-text-field>
                <v-btn type="submit">Search</v-btn>
            </form> -->

            <!-- Search results -->
            <search-results
                api-endpoint="{{ route('api.search.post') }}"
                search-query="{{ $query }}"
                tag-overview-href="{{ route('tags') }}"
                search-placeholder-text="@lang('search.search_placeholder')"
                no-results-text="@lang('search.no_results')"
                enter-query-text="@lang('search.enter_query')"
                user-type-text="@lang('search.user_type')"
                task-type-text="@lang('search.task_type')"
                project-type-text="@lang('search.project_type')"
                ministry-type-text="@lang('search.ministry_type')"
                organization-type-text="@lang('search.organization_type')"
                results-text="@lang('search.results')">
            </search-results>

            <!-- Footer actions -->
            <div id="search-footer">
                <v-btn text href="{{ route('tags') }}">
                    @lang("search.view_tags")
                </v-btn>
            </div>

        </div>
    </div>
@stop