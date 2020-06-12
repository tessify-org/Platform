@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("search") !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/web_search.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("search.title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")
            
            <!-- Search results -->
            <search-results
                search-query="{{ $query }}"
                api-endpoint="{{ route('api.search.post') }}"
                tag-overview-href="{{ route('tags') }}"
                :strings="{{ $strings->toJson() }}"
                locale="{{ app()->getLocale() }}">
            </search-results>

        </div>
    </div>
    
@stop