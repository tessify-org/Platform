@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("reviews") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/feedback.svg') }});"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("reviews.overview_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- My reviews -->
            <div id="my-reviews">

                <!-- Outstanding review requests -->
                <review-request-overview
                    :requests="{{ $requests->toJson() }}"
                    :strings="{{ $outstandingStrings->toJson() }}">
                </review-request-overview>

                <!-- Review overview -->
                <review-overview
                    :reviews="{{ $reviews->toJson() }}"
                    :strings="{{ $overviewStrings->toJson() }}">
                </review-overview>

            </div>

        </div>
    </div>

@stop
