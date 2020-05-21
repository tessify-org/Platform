@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("groups") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header">
        <div id="page-header__bg" style="background-image: url({{ asset('storage/images/groups/headers/default.jpg') }})"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">

            <!-- Title & subtitle -->
            <h1 id="page-header__title">@lang("groups.overview_title")</h1>
            <h2 id="page-header__subtitle">@lang("groups.overview_subtitle")</h2>

            <!-- Actions -->
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