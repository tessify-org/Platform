@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tasks.abandon", $task) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/windy_day.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("tasks.abandon_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Abandon task -->
            <div id="abandon-task">
                <div id="abandon-task__left">

                    <!-- Task info -->
                    <div id="task-info" class="elevation-1">
                        <div id="task-info__header" style="background-image: url({{ asset($task->header_image_url) }});"></div>
                        <div id="task-info__content">
                            <h3 id="task-info__title">{{ $task->title }}</h3>
                            <div id="task-info__description">
                                {{ Str::limit($task->description, 300, '..') }}
                            </div>
                        </div>
                    </div>

                </div>
                <div id="abandon-task__right">

                    <!-- Form -->
                    <form action="{{ route('tasks.abandon.post', ['slug' => $task->slug]) }}" method="post">
                        @csrf
                        <task-abandon-form
                            locale="{{ app()->getLocale() }}"
                            :task="{{ $task->toJson() }}"
                            :errors="{{ $errors->toJson() }}"
                            :strings="{{ $strings->toJson() }}"
                            :old-input="{{ $oldInput->toJson() }}"
                            back-href="{{ route('tasks.view', ['slug' => $task->slug]) }}">
                        </task-abandon-form>
                    </form>

                </div>
            </div>

        </div>
    </div>

@stop