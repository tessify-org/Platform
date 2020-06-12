@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects.team.apply", $project) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow">
        <div id="page-header__bg" style="background-image: url({{ asset($project->header_image_url) }})"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("projects.apply_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="apply-for-project" class="content-section__wrapper">
        <div class="content-section">

            <!-- Form -->
            <form action="{{ route('projects.team.apply.post', $project->slug) }}" method="post">
                @csrf
                <apply-for-team-form
                    :project="{{ $project->toJson() }}"
                    :roles="{{ $roles->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    back-href="{{ route('projects.team', $project->slug) }}"
                    back-text="@lang('projects.apply_back')"
                    submit-text="@lang('projects.apply_submit')"
                    project-text="@lang('projects.apply_form_project')"
                    role-text="@lang('projects.apply_form_role')"
                    motivation-text="@lang('projects.apply_form_motivation')">
                </apply-for-team-form>
            </form>

        </div>
    </div>
@stop