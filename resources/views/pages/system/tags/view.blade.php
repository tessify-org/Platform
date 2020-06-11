@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tags.view", $tag) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="very-narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">

            <!-- Title & subtitle -->
            <h1 id="page-header__title">{{ ucfirst(strtolower($tag->name)) }}</h1>
            <h1 id="page-header__subtitle">@lang("tags.view_title")</h1>
            
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">
        
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Projects -->
            <div class="content-card elevation-1 mb">
                <div class="content-card__content">
                    <h3 class="content-subtitle">@lang("tags.view_projects")</h3>
                    @if ($tag->projects->count())
                        <div class="tag-overview">
                            @foreach ($tag->projects as $project)
                                <a class="tag-overview__entry" href="{{ route('projects.view', $project->slug) }}">
                                    <span class="entry-col">{{ $project->title }}</span>
                                    <span class="entry-col">{{ $project->status->label }}</span>
                                    <span class="entry-col">{{ $project->author->formatted_name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        @lang("tags.view_no_projects")
                    @endif
                </div>
            </div>

            <!-- Tasks -->
            <div class="content-card elevation-1">
                <div class="content-card__content">
                    <h3 class="content-subtitle">@lang("tags.view_tasks")</h3>
                    @if ($tag->tasks->count())
                        <div class="tag-overview">
                            @foreach ($tag->tasks as $task)
                                <a class="tag-overview__entry" href="{{ route('tasks.view', $task->slug) }}">
                                    <span class="entry-col">{{ $task->title }}</span>
                                    <span class="entry-col">{{ $task->status->label }}</span>
                                    <span class="entry-col">{{ $task->author->formatted_name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        @lang("tags.view_no_tasks")
                    @endif
                </div>
            </div>

        </div>
    </div>
@stop