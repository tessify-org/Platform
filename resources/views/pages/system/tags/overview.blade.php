@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tags") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="very-narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/logistics.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("tags.overview_title")</h1>
            </div>
        </div>
    </div>

    <!-- Overview -->
    <div class="content-section__wrapper">
        <div class="content-section">
        
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Tags -->
            <tag-overview
                :tags="{{ $tags->toJson() }}"
                :strings="{{ $strings->toJson() }}">
            </tag-overview>

        </div>
    </div>

@stop