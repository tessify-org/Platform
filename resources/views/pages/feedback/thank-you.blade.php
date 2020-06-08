@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("feedback-submitted") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">
                @lang("feedback.thank_you_title")
            </h1>

            <div id="bug-report-submitted">
                <div id="bug-report-submitted__image-wrapper">
                    <img id="bug-report-submitted__image" src="{{ asset('storage/images/undraw/bug_fixing.svg') }}">
                </div>
                <div id="bug-report-submitted__text">
                    {!! nl2br(__("feedback.thank_you_text")) !!}
                </div>
            </div>

        </div>
    </div>
@stop
