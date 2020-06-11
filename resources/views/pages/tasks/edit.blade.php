@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tasks.edit", $task) !!}
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
                <h1 id="page-header__title" class="no-margin">@lang("tasks.edit_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")
            
            <!-- Form -->
            <form action="{{ route('tasks.edit.post', ['slug' => $task->slug]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <task-form
                    :task="{{ $task->toJson() }}"
                    :skills="{{ $skills->toJson() }}"
                    :ministries="{{ $ministries->toJson() }}"
                    :organizations="{{ $organizations->toJson() }}"
                    :departments="{{ $departments->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    :projects="{{ $projects->toJson() }}"
                    :statuses="{{ $statuses->toJson() }}"
                    :categories="{{ $categories->toJson() }}"
                    :seniorities="{{ $seniorities->toJson() }}"
                    :groups="{{ $groups->toJson() }}"
                    :tags="{{ $tags->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    back-href="{{ route('tasks.view', $task->slug) }}"
                    locale="{{ app()->getLocale() }}">
                </task-form>
            </form>

        </div>
    </div>

@stop