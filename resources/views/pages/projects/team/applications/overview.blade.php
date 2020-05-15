@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects.team.applications", $project) !!}
@stop

@section("content")
    <div id="project">

        <!-- Header -->
        <div id="project-header" style="background-image: url({{ asset($project->header_image_url) }});">
            <div id="project-header__overlay"></div>
            <div id="project-header__content" class="content-section">

                <div id="project-header__text">
                    <h1 id="project-header__title">{{ $project->title }}</h1>
                    <h2 id="project-header__slogan">{{ $project->slogan }}</h2>
                </div>

            </div>
        </div>

        <!-- Content -->
        <div id="project-content" class="content-section__wrapper">
            <div class="content-section pt50">

                <!-- Feedback -->
                @include("partials.feedback")

                <!-- Navigation -->
                @include("partials.projects.navigation", [
                    "page" => "team-applications",
                    "project" => $project,    
                ])

                <!-- Project's team member applications -->
                <div id="project-member-applications" class="content-box elevation-1">

                    <h3 class="content-subtitle">
                        @lang("projects.view_applications_manage_title")
                    </h3>

                    @if ($applications->count())
                        <project-team-application-list
                            :applications="{{ $applications->toJson() }}"
                            no-applications-text="@lang('projects.view_applications_my_empty')"
                            accepted-text="@lang('general.accepted')"
                            rejected-text="@lang('general.rejected')"
                            pending-text="@lang('general.pending')">
                        </project-team-application-list>
                    @else
                        <div id="no-applications">
                            @lang("projects.view_applications_manage_title")
                        </div>
                    @endif

                </div>

                <!-- My applications -->
                <div id="project-my-applications" class="content-box elevation-1">
                    <h3 class="content-subtitle">
                        @lang("projects.view_applications_my_title")
                    </h3>
                    @if ($myApplications->count())
                        <project-team-application-list
                            :applications="{{ $myApplications->toJson() }}"
                            no-applications-text="@lang('projects.view_applications_my_empty')"
                            accepted-text="@lang('general.accepted')"
                            rejected-text="@lang('general.rejected')"
                            pending-text="@lang('general.pending')">
                        </project-team-application-list>
                    @else
                        <div id="no-applications">
                            @lang("projects.view_applications_my_empty")
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
@stop