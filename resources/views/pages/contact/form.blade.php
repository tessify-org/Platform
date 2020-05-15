@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("contact") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">@lang("static.contact_title")</h1>

        </div>
    </div>
@stop
