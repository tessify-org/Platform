@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("group.applications", $group) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- View group -->
            <div id="view-group">
                <div id="view-group__sidebar">

                    @include("partials.groups.view-sidebar", [
                        "group" => $group,
                        "page" => "applications",
                    ])

                </div>
                <div id="view-group__content">

                    <!-- Group -->
                    <div id="group" class="elevation-2">

                        <!-- Group header -->
                        <div id="group-header">
                            <div id="group-header__bg" style="background-image: url({{ asset($group->header_image_url) }})"></div>
                            <div id="group-header__bg-overlay"></div>
                            <div id="group-header__text">
                                <h1 id="group-title">{{ $group->name }}</h1>
                                <h2 id="group-subtitle">@lang("groups.applications_title")</h2>
                            </div>
                        </div>

                        <!-- Content -->
                        <div id="group-content" class="no-padding">

                            <!-- Group member overview -->
                            <group-application-overview
                                :applications="{{ $applications->toJson() }}"
                                :strings="{{ $strings->toJson() }}"
                                :api-endpoints="{{ $apiEndpoints->toJson() }}"
                                :can-manage="{{ json_encode(auth()->user()->can('manage-group-applications', $group)) }}">
                            </group-application-overview>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
@stop