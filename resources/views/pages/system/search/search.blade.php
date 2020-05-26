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
                :strings="{{ $strings->toJson() }}"
                locale="{{ app()->getLocale() }}">
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