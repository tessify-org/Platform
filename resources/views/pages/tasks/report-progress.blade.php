@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tasks.report-progress", $task) !!}
@stop

@section("content")
    <div id="team-member-application">

        <!-- Page header -->
        <div id="page-header" class="very-narrow light">
            <div id="page-header__bg"></div>
            <div id="page-header__bg-illustration">
                <div id="bg-illustration__wrapper">
                    <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/add_file.svg') }})"></div>
                </div>
            </div>
            <div id="page-header__content" class="align-left">
                <div id="page-header__content-wrapper">
                    <h1 id="page-header__title" class="no-margin">@lang("tasks.report_progress_title")</h1>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content-section__wrapper">
            <div class="content-section">
                <div id="report-progress">

                    <!-- Feedback -->
                    @include("partials.feedback")

                    <!-- Form -->
                    <form action="{{ route('tasks.report-progress.post', ['slug' => $task->slug]) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <report-task-progress-form
                            :errors="{{ $errors->toJson() }}"
                            :old-input="{{ $oldInput->toJson() }}"
                            :strings="{{ $strings->toJson() }}"
                            back-href="{{ route('tasks.view', ['slug' => $task->slug]) }}">
                        </report-task-progress-form>

                    </form>

                </div>
            </div>
        </div>
    </div>
@stop