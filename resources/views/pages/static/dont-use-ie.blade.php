@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("dont-use-ie") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Don't use IE -->
            <div id="dont-use-ie">

                <!-- Title -->
                <h1 class="page-title centered">@lang("static.dont_use_ie_title")</h1>

                <!-- Text -->
                <div id="dont-use-ie__text">@lang("static.dont_use_ie_text")</div>

            </div>

        </div>
    </div>
@stop
