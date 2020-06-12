@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects.resources", $project) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="white-border-bottom">
        <div id="page-header__bg" style="background-image: url({{ asset($project->header_image_url) }})"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title">{{ $project->title }}</h1>
                @if ($project->slogan)
                    <h2 id="page-header__subtitle">{{ $project->slogan }}<h2>
                @endif
            </div>
        </div>
        <div id="page-header__actions-wrapper">
            <div id="page-header__actions" class="align-right">
                @if (!auth()->user()->hasSubscribed($project))
                    <v-btn color="white" href="{{ route('projects.subscribe', ['slug' => $project->slug]) }}">
                        <i class="fas fa-check-circle"></i>
                        @lang("projects.view_subscribe")
                    </v-btn>
                @else
                    <v-btn color="white" href="{{ route('projects.unsubscribe', ['slug' => $project->slug]) }}">
                        <i class="fas fa-times-circle"></i>
                        @lang("projects.view_unsubscribe")
                    </v-btn>
                @endif
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Task page -->
            <div id="view-project">
                <aside id="view-project__sidebar">

                    @include("partials.projects.view-sidebar", [
                        "project" => $project,
                        "page" => "resources",
                    ])

                </aside>
                <main id="view-project__content">

                    <!-- Form -->
                    <form action="{{ route('projects.resources.create.post', $project->slug) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <project-resource-form
                            :errors="{{ $errors->toJson() }}"
                            :strings="{{ $strings->toJson() }}"
                            :api-endpoints="{{ $apiEndpoints->toJson() }}"
                            back-href="{{ route('projects.resources', $project->slug) }}">
                        </project-resource-form>
                    </form>

                </main>
            </div>

        </div>
    </div>
@stop