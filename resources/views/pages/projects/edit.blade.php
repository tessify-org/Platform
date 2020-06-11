@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects.edit", $project) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/engineers.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("projects.update_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('projects.edit.post', $project->slug) }}" method="post" enctype="multipart/form-data">
                @csrf
                <project-form
                    :project="{{ $project->toJson() }}"
                    :project-phases="{{ $phases->toJson() }}"
                    :project-statuses="{{ $statuses->toJson() }}"
                    :project-categories="{{ $categories->toJson() }}"
                    :skills="{{ $skills->toJson() }}"
                    :ministries="{{ $ministries->toJson() }}"
                    :organizations="{{ $organizations->toJson() }}"
                    :departments="{{ $departments->toJson() }}"
                    :tags="{{ $tags->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    back-href="{{ route('projects') }}"
                    locale="{{ app()->getLocale() }}"
                    create-resource-api-endpoint="{{ route('api.projects.resources.create.post') }}"
                    update-resource-api-endpoint="{{ route('api.projects.resources.update.post') }}"
                    delete-resource-api-endpoint="{{ route('api.projects.resources.delete.post') }}">
                </project-form>
            </form>

        </div>
    </div>

@stop