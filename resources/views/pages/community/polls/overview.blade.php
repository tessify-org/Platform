@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("polls") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/segment_analysis.svg') }});"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">

                <!-- Title & subtitle -->
                <h1 id="page-header__title">@lang("polls.overview_title")</h1>
                <h2 id="page-header__subtitle" class="no-margin">@lang("polls.overview_subtitle")</h2>

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
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- My polls -->
            @if ($myPolls->count())
                <h3 class="content-title">@lang("polls.overview_my_polls")</h3>
                <my-poll-overview
                    :polls="{{ $myPolls->toJson() }}"
                    :strings="{{ $myPollsStrings->toJson() }}"
                    locale="{{ app()->getLocale() }}">
                </my-poll-overview>
            @endif
            
            <!-- Public polls -->
            <h3 class="content-title">@lang("polls.overview_public_polls")</h3>
            <poll-overview
                :polls="{{ $polls->toJson() }}"
                :strings="{{ $strings->toJson() }}"
                locale="{{ app()->getLocale() }}">
            </poll-overview>
            
        </div>
    </div>

@stop