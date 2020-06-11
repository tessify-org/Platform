@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tasks.progress-report.review", $task, $report) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__content">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("tasks.review_progress_report_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">
            <div id="review-progress-report">

                <!-- Feedback -->
                @include("partials.feedback")

                <!-- Form -->
                <form action="{{ route('tasks.progress-report.review.post', ['slug' => $task->slug, 'uuid' => $report->uuid]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <review-progress-report-form
                        :report="{{ $report->toJson() }}"
                        :errors="{{ $errors->toJson() }}"
                        :old-input="{{ $oldInput->toJson() }}"
                        message-text="@lang('tasks.review_progress_report_message')"
                        back-href="{{ route('tasks.progress-report', ['slug' => $task->slug, 'uuid' => $report->uuid]) }}"
                        back-text="@lang('tasks.review_progress_report_back')"
                        submit-text="@lang('tasks.review_progress_report_submit')">
                    </review-progress-report-form>
                </form>

            </div>
        </div>
    </div>

@stop