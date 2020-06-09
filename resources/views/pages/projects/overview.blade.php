@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects") !!}
@stop

@section("content")
    <div id="project-overview">

        <!-- Page header -->
        <div id="page-header" class="narrow">
            <div id="page-header__bg"></div>
            <div id="page-header__bg-overlay"></div>
            <div id="page-header__bg-illustration">
                <div id="bg-illustration__wrapper">
                    <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/organizing_projects.svg') }})"></div>
                </div>
            </div>
            <div id="page-header__content" class="align-left">
                <div id="page-header__content-wrapper">
                    <h1 id="page-header__title" style="margin-left: -5px;">@lang("projects.overview_title")</h1>
                    <h2 id="page-header__subtitle" class="no-margin">@lang("projects.overview_subtitle", ["num_projects" => $projects->count()])</h2>
                    <div id="page-header__actions">
                        <div class="page-header__action">
                            <v-btn color="primary" outlined href="{{ route('projects.create') }}">
                                @lang("projects.overview_create_cta")
                            </v-btn>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div id="project-overview__content" class="content-section__wrapper">
            <div class="content-section pt50">
                
                <!-- Feedback -->
                @include("partials.feedback")

                <!-- Projects -->
                <project-overview
                    locale="{{ app()->getLocale() }}"
                    :projects="{{ $projects->toJson() }}"
                    :statuses="{{ $statuses->toJson() }}"
                    :categories="{{ $categories->toJson() }}"
                    :strings="{{ $strings->toJson() }}">
                </project-overview>

                <!-- Dashboard
                <div id="project-dashboard">
                    <div id="project-dashboard__sidebar">

                        <project-dashboard-sidebar-search-bar
                            title="@lang('projects.overview_sidebar_search')">
                        </project-dashboard-sidebar-search-bar>

                        <project-dashboard-sidebar-statuses
                            :statuses="{{ $statuses->toJson() }}"
                            title="@lang('projects.overview_sidebar_statuses')"
                            hint="@lang('projects.overview_sidebar_statuses_hint')"
                            no-records-text="@lang('projects.overview_sidebar_statuses_empty')">
                        </project-dashboard-sidebar-statuses>
                        
                        <project-dashboard-sidebar-categories
                            :categories="{{ $categories->toJson() }}"
                            title="@lang('projects.overview_sidebar_categories')"
                            hint="@lang('projects.overview_sidebar_categories_hint')"
                            no-records-text="@lang('projects.overview_sidebar_categories_empty')">
                        </project-dashboard-sidebar-categories>

                    </div>
                    <div id="project-dashboard__content">

                        <project-dashboard-overview
                            :projects="{{ $projects->toJson() }}"
                            description-text="@lang('projects.overview_description')"
                            view-text="@lang('projects.overview_view')"
                            no-projects-text="@lang('projects.overview_no_projects')">
                        </project-dashboard-overview>

                    </div>
                </div>
                -->
                
            </div>
        </div>
        
    </div>
@stop