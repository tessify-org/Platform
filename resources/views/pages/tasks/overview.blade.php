@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tasks") !!}
@stop

@section("content")
    <div id="task-overview">

        <!-- Page header -->
        <div id="page-header" class="narrow">
            <div id="page-header__bg"></div>
            <div id="page-header__bg-overlay"></div>
            <div id="page-header__bg-illustration">
                <div id="bg-illustration__wrapper">
                    <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/maker_launch.svg') }})"></div>
                </div>
            </div>
            <div id="page-header__content" class="align-left">
                <div id="page-header__content-wrapper">
                    <h1 id="page-header__title" style="margin-left: -5px;">@lang("tasks.overview_title")</h1>
                    <h2 id="page-header__subtitle" class="no-margin">@lang("tasks.overview_subtitle")</h2>
                    <div id="page-header__actions">
                        <div class="page-header__action">
                            <v-btn color="primary" outlined href="{{ route('tasks.create') }}">
                                @lang("tasks.overview_create_cta")
                            </v-btn>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div id="task-overview__content" class="content-section__wrapper">
            <div class="content-section pt50">
                
                <!-- Feedback -->
                @include("partials.feedback", ["extraMargin" => true])
                
                <!-- Dashboard columns -->
                <div id="task-dashboard">
                    <div id="task-dashboard__sidebar">

                        <!-- Search bar -->
                        <task-dashboard-sidebar-search-bar
                            title="@lang('tasks.overview_sidebar_search')"
                            hint="@lang('tasks.overview_sidebar_search_hint')">
                        </task-dashboard-sidebar-search-bar>

                        <!-- Status -->
                        <task-dashboard-sidebar-statuses
                            locale="{{ app()->getLocale() }}"
                            :statuses="{{ $statuses->toJson() }}"
                            title="@lang('tasks.overview_sidebar_statuses')"
                            hint="@lang('tasks.overview_sidebar_search_hint')"
                            no-records-text="@lang('tasks.overview_sidebar_statuses_empty')">
                        </task-dashboard-sidebar-statuses>

                        <!-- Categories -->
                        <task-dashboard-sidebar-categories
                            locale="{{ app()->getLocale() }}"
                            :categories="{{ $categories->toJson() }}"
                            title="@lang('tasks.overview_sidebar_categories')"
                            hint="@lang('tasks.overview_sidebar_categories_hint')"
                            no-records-text="@lang('tasks.overview_sidebar_categories_empty')">
                        </task-dashboard-sidebar-categories>

                        <!-- Skills -->
                        <task-dashboard-sidebar-skills
                            locale="{{ app()->getLocale() }}"
                            :skills="{{ $skills->toJson() }}"
                            title="@lang('tasks.overview_sidebar_skills')"
                            hint="@lang('tasks.overview_sidebar_skills_hint')"
                            no-records-text="@lang('tasks.overview_sidebar_skills_empty')">
                        </task-dashboard-sidebar-skills>

                        <!-- Seniorities -->
                        <task-dashboard-sidebar-seniorities
                            locale="{{ app()->getLocale() }}"
                            :seniorities="{{ $seniorities->toJson() }}"
                            title="@lang('tasks.overview_sidebar_seniorities')"
                            hint="@lang('tasks.overview_sidebar_seniorities_hint')"
                            no-records-text="@lang('tasks.overview_sidebar_seniorities_empty')">
                        </task-dashboard-sidebar-seniorities>

                        <!-- Duration -->
                        <task-dashboard-sidebar-duration
                            title="@lang('tasks.overview_sidebar_timespan')"
                            hint="@lang('tasks.overview_sidebar_timespan_hint')">
                        </task-dashboard-sidebar-duration>

                    </div>
                    <div id="task-dashboard__content">

                        <!-- Task overview -->
                        <task-dashboard-overview
                            :tasks="{{ $tasks->toJson() }}"
                            locale="{{ app()->getLocale() }}"
                            description-text="@lang('tasks.overview_description')"
                            skills-text="@lang('tasks.overview_skills')"
                            complexity-text="@lang('tasks.overview_complexity')"
                            view-text="@lang('tasks.overview_view')"
                            no-tasks-text="@lang('tasks.overview_no_tasks')">
                        </task-dashboard-overview>

                    </div>
                </div>

            </div>
        </div>
    </div>
@stop