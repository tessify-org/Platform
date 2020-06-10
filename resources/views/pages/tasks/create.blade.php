@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tasks.create", $project) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/add_files.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("tasks.create_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Open form -->
            <form action="{{ route('tasks.create.post') }}" method="post" enctype="multipart/form-data">
                @csrf

                <!-- Render form -->
                <task-form
                    :project="{{ is_null($project) ? json_encode(null) : $project->toJson() }}"
                    :skills="{{ $skills->toJson() }}"
                    :ministries="{{ $ministries->toJson() }}"
                    :organizations="{{ $organizations->toJson() }}"
                    :departments="{{ $departments->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    :projects="{{ $projects->toJson() }}"
                    :categories="{{ $categories->toJson() }}"
                    :seniorities="{{ $seniorities->toJson() }}"
                    :groups="{{ $groups->toJson() }}"
                    :tags="{{ $tags->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    back-href="{{ is_null($project) ? route('tasks') : route('projects.tasks', $project->slug) }}"
                    locale="{{ app()->getLocale() }}">
                </task-form>
                
            </form>
            
        </div>
    </div>

@stop