@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects.team.roles.create", $project) !!}
@stop

@section("content")
    <!-- Content -->
    <div id="apply-for-project" class="content-section__wrapper">
        <div class="content-section">

            <h1 id="delete-dialog__title" class="page-title centered">
                @lang("projects.create_role_title")
            </h1>

            <form action="{{ route('projects.team.roles.create.post', $project->slug) }}" method="post">
                @csrf
                
                <project-team-role-form
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    back-href="{{ route('projects.team.view', $project->slug) }}"
                    locale="{{ app()->getLocale() }}">
                </project-team-role-form>
                
            </form>

        </div>
    </div>
@stop