@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects.team", $project) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="white-border-bottom">
        <div id="page-header__bg" style="background-image: url({{ asset($project->header_image_url) }})"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title">{{ $project->title }}</h1>
                @if ($project->slogan)
                    <h2 id="page-header__subtitle">{{ $project->slogan }}<h2>
                @endif
            </div>
        </div>
        <div id="page-header__actions-wrapper">
            <div id="page-header__actions" class="align-right">
                @if (!auth()->user()->hasSubscribed($project))
                    <v-btn color="white" href="{{ route('projects.subscribe', ['slug' => $project->slug]) }}">
                        <i class="fas fa-check-circle"></i>
                        @lang("projects.view_subscribe")
                    </v-btn>
                @else
                    <v-btn color="white" href="{{ route('projects.unsubscribe', ['slug' => $project->slug]) }}">
                        <i class="fas fa-times-circle"></i>
                        @lang("projects.view_unsubscribe")
                    </v-btn>
                @endif
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Task page -->
            <div id="view-project">
                <aside id="view-project__sidebar">

                    @include("partials.projects.view-sidebar", [
                        "project" => $project,
                        "page" => "team",
                    ])
                    
                </aside>
                <main id="view-project__content">
                    
                    <!-- Team member overview -->
                    <project-team-member-overview
                        :project="{{ $project->toJson() }}"
                        :members="{{ $members->toJson() }}"
                        :strings="{{ $memberOverviewStrings->toJson() }}"
                        :api-endpoints="{{ $memberOverviewApiEndpoints->toJson() }}">
                    </project-team-member-overview>
                    
                    <!-- Page controls -->
                    <div class="page-controls">
                        <div class="page-controls__left">
                            <v-btn color="white" href="{{ route('projects.view', $project->slug) }}">
                                <i class="fas fa-arrow-left"></i>
                                @lang("projects.back_to_project")
                            </v-btn>
                        </div>
                        <div class="page-controls__right">
                            <v-btn color="primary" href="{{ route('projects.team.applications', $project->slug) }}">
                                <i class="fas fa-user-plus"></i>
                                @lang("projects.view_applications")
                            </v-btn>
                        </div>
                    </div>

                </main>
            </div>

        </div>
    </div>
@stop