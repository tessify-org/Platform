@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("group.delete", $group) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title & subtitle -->
            <h1 class="page-title centered">@lang("groups.delete_title")</h1>

            <!-- Feedback -->
            @include("partials.feedback")
            
            
            
        </div>
    </div>
@stop