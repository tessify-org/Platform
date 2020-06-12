@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects.team.leave", $project) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/login.svg') }}); height: 160px;"></div>
            </div>
        </div>
        <div id="page-header__content">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("projects.leave_team_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">
            
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('projects.team.leave.post', $project->slug) }}" method="post">
                {{ csrf_field() }}

                <div id="delete-dialog" class="elevation-1">
                    <div id="delete-dialog__text">
                        {!! nl2br(__('projects.leave_team_text')) !!}
                    </div>
                    <div id="delete-dialog__actions">
                        <div id="delete-dialog__actions-left">
                            <v-btn href="{{ route('projects.team', $project->slug) }}" outlined>
                                <i class="fas fa-arrow-left"></i>
                                @lang("projects.leave_team_back")
                            </v-btn>
                        </div>
                        <div id="delete-dialog__actions-right">
                            <v-btn type="submit" color="red" dark depressed>
                                <i class="fas fa-trash-alt"></i>
                                @lang("projects.leave_team_submit")
                            </v-btn>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

@stop