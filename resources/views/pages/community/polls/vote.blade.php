@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("poll.vote") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title & subtitle -->
            <h1 class="page-title centered">@lang("polls.vote_title")</h1>
            
            <!-- Feedback -->
            @include("partials.feedback")
            
        </div>
    </div>
@stop