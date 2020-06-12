@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("groups") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/community.svg') }});"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title">@lang("groups.overview_title")</h1>
                <h2 id="page-header__subtitle" class="no-margin">@lang("groups.overview_subtitle")</h2>
                <div id="page-header__actions">
                    <div class="page-header__action">
                        <v-btn color="primary" href="{{ route('groups.create') }}">
                            <i class="fas fa-plus"></i>
                            @lang("groups.overview_create")
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
            
            <!-- Group overview -->
            <group-overview
                :groups="{{ $groups->toJson() }}"
                :strings="{{ $strings->toJson() }}"
                locale="{{ app()->getLocale() }}">
            </group-overview>
            
        </div>
    </div>

@stop