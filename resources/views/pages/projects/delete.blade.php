@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects.delete", $project) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 id="delete-dialog__title" class="page-title centered">
                @lang("projects.delete_title")
            </h1>
        
            @include("partials.feedback")

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