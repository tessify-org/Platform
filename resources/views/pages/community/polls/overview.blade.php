@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("polls") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">

            <!-- Title & subtitle -->
            <h1 id="page-header__title">@lang("polls.overview_title")</h1>
            <h2 id="page-header__subtitle">@lang("polls.overview_subtitle")</h2>

            <!-- Actions -->
            <div id="page-header__actions">
                <div class="page-header__action">
                    <v-btn color="primary" href="{{ route('polls.create') }}">
                        <i class="fas fa-plus"></i>
                        @lang("polls.overview_create")
                    </v-btn>
                </div>
            </div>

        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")
            
            <!-- Poll overview -->
            <poll-overview
                :polls="{{ $polls->toJson() }}"
                :strings="{{ $strings->toJson() }}"
                locale="{{ app()->getLocale() }}">
            </poll-overview>
            
        </div>
    </div>

@stop