@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("polls") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title & subtitle -->
            <h1 class="page-title centered">@lang("polls.overview_title")</h1>
            <h2 class="page-subtitle centered">@lang("polls.overview_subtitle")</h2>
            
            <!-- Feedback -->
            @include("partials.feedback")
            
        </div>
    </div>
@stop