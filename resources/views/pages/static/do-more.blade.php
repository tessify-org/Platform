@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("do-more") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">@lang("static.do_more_title")</h1>

        </div>
    </div>
@stop
