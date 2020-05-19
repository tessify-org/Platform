@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects.create") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">
                @lang("projects.create_title")
            </h1>
        
            @include("partials.feedback")

            <form action="{{ route('projects.create.post') }}" method="post" enctype="multipart/form-data">
                @csrf

                <project-form
                    :project-phases="{{ $phases->toJson() }}"
                    :project-statuses="{{ $statuses->toJson() }}"
                    :project-categories="{{ $categories->toJson() }}"
                    :ministries="{{ $ministries->toJson() }}"
                    :organizations="{{ $organizations->toJson() }}"
                    :departments="{{ $departments->toJson() }}"
                    :skills="{{ $skills->toJson() }}"
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