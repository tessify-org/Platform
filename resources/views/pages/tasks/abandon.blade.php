@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tasks.abandon", $task) !!}
@stop

@section("content")

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title -->
            <h1 class="page-title centered">@lang("tasks.abandon_title")</h1>

            <!-- Feedback -->
            @include("partials.feedback")

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

@stop