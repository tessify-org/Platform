@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects.delete", $project) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/throw_away.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("projects.delete_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('projects.delete.post', $project->slug) }}" method="post">
                @csrf
                <div id="delete-dialog" class="elevation-1">
                    <div id="delete-dialog__text">
                        {!! nl2br(__('projects.delete_text', ['title' => $project->title])) !!}
                    </div>
                    <div id="delete-dialog__actions">
                        <div id="delete-dialog__actions-left">
                            <v-btn href="{{ route('projects.view', $project->slug) }}" outlined>
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

@stop