@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tasks.progress-reports", $task) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="white-border-bottom">
        <div id="page-header__bg" style="background-image: url({{ asset($task->header_image_url) }})"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title">{{ $task->title }}</h1>
                @if ($task->project)
                    <h2 id="page-header__subtitle" class="no-margin">
                        {!! __("tasks.view_part_of_project", ["title" => $task->project->title]) !!}
                    </h2>
                @endif
            </div>
        </div>
        <div id="page-header__actions-wrapper">
            <div id="page-header__actions" class="align-right">
                @if (!auth()->user()->hasSubscribed($task))
                    <v-btn color="white" href="{{ route('tasks.subscribe', ['slug' => $task->slug]) }}">
                        <i class="fas fa-check-circle"></i>
                        @lang("tasks.view_subscribe")
                    </v-btn>
                @else
                    <v-btn color="white" href="{{ route('tasks.unsubscribe', ['slug' => $task->slug]) }}">
                        <i class="fas fa-times-circle"></i>
                        @lang("tasks.view_unsubscribe")
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
            <div id="view-task">
                <aside id="view-task__sidebar">

                    @include("partials.tasks.view-sidebar", [
                        "task" => $task,
                        "page" => "progress-reports",
                    ])

                </aside>
                <main id="view-task__content">
                    
                    <!-- Progress report overview -->
                    <task-progress-report-overview
                        :task="{{ $task->toJson() }}"
                        :reports="{{ $reports->toJson() }}"
                        :strings="{{ $strings->toJson() }}">
                    </task-progress-report-overview>

                    <!-- Page controls -->
                    <div class="page-controls">
                        <div class="page-controls__left">
                            <v-btn color="white" href="{{ route('tasks.view', ['slug' => $task->slug]) }}">
                                <i class="fas fa-arrow-left"></i>
                                @lang("tasks.back")
                            </v-btn>
                        </div>
                    </div>
                    
                </main>
            </div>

        </div>
    </div>
@stop