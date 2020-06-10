@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tasks.delete", $task) !!}
@stop

@section("content")
    <div id="team-member-application">

        <!-- Page header -->
        <div id="page-header" class="very-narrow">
            <div id="page-header__bg"></div>
            <div id="page-header__bg-overlay"></div>
            <div id="page-header__bg-illustration">
                <div id="bg-illustration__wrapper">
                    <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/throw_away.svg') }})"></div>
                </div>
            </div>
            <div id="page-header__content" class="align-left">
                <div id="page-header__content-wrapper">
                    <h1 id="page-header__title" class="no-margin">@lang("tasks.delete_title")</h1>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-section__wrapper">
            <div class="content-section">

                <!-- Feedback -->
                @include("partials.feedback")

                <!-- Form -->
                <form action="{{ route('tasks.delete.post', ['slug' => $task->slug]) }}" method="post">
                    @csrf
                    <div id="delete-dialog" class="elevation-1">
                        <div id="delete-dialog__text">
                            {!! nl2br(__('tasks.delete_text', ['title' => $task->title])) !!}
                        </div>
                        <div id="delete-dialog__actions">
                            <div id="delete-dialog__actions-left">
                                <v-btn href="{{ route('tasks.view', ['slug' => $task->slug]) }}" outlined>
                                    <i class="fas fa-arrow-left"></i>
                                    @lang("projects.delete_cancel")
                                </v-btn>
                            </div>
                            <div id="delete-dialog__actions-right">
                                <v-btn type="submit" color="red" dark depressed>
                                    <i class="fas fa-trash-alt"></i>
                                    @lang("projects.delete_confirm")
                                </v-btn>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
@stop