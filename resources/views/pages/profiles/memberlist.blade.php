@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("memberlist") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/meet_the_team.svg') }});"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">

                <!-- Title & subtitle -->
                <h1 id="page-header__title" class="no-margin">@lang("profiles.memberlist_title")</h1>
                
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Memberlist -->
            <memberlist
                :users="{{ $users->toJson() }}"
                :ministries="{{ $ministries->toJson() }}"
                :organizations="{{ $organizations->toJson() }}"
                :strings="{{ $strings->toJson() }}"
                locale="{{ app()->getLocale() }}">
            </memberlist>

        </div>
    </div>

@stop