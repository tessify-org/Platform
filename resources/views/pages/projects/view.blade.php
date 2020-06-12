@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("projects.view", $project) !!}
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
    
    <!-- Content wrapper -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- View project -->
            <div id="view-project">
                <div id="view-project__sidebar">

                    <!-- Sidebar -->
                    @include("partials.projects.view-sidebar", [
                        "project" => $project,
                        "page" => "info",
                    ])

                </div>
                <div id="view-project__content">
                    
                    <!-- Sign up -->
                    @if ($project->status->name != "closed" && !$project->is_team_member && !$project->has_outstanding_application)
                        <h3 class="content-card__small-title">@lang("projects.view_sign_up")</h3>
                        <div id="project__sign-up" class="content-card elevation-2">
                            <div id="sign-up__text">
                                <div id="sign-up__title">@lang("projects.view_sign_up_title")</div>
                                <div id="sign-up__description">@lang("projects.view_sign_up_description")</div>
                                <div id="sign-up__actions">
                                    <v-btn depressed color="white" href="{{ route('projects.team.apply', $project->slug) }}">
                                        <i class="far fa-thumbs-up"></i>
                                        @lang("projects.view_join")
                                    </v-btn>
                                </div>
                            </div>
                            <div id="sign-up__illustration" style="background-image: url({{ asset('storage/images/undraw/freelancer_white.svg') }});"></div>
                        </div>
                    @endif

                    <!-- Pending application -->
                    @if ($project->has_outstanding_application)
                        <h3 class="content-card__small-title">@lang("projects.view_pending_application")</h3>
                        <div id="project__pending-application" class="content-card elevation-2">
                            <div id="pending-application__text">
                                <div id="pending-application__title">@lang("projects.view_pending_application_title")</div>
                                <div id="pending-application__description">@lang("projects.view_pending_application_description")</div>
                            </div>
                            <div id="pending-application__illustration" style="background-image: url({{ asset('storage/images/undraw/loading_white.svg') }});"></div>
                        </div>
                    @endif

                    <!-- Project information -->
                    <div id="project">

                        <!-- Description & Tags -->
                        <h3 class="content-card__small-title">@lang("projects.view_description")</h3>
                        <div class="content-card elevation-2 mb">
                            <div class="content-card__content">

                                <!-- Description -->
                                <div id="project-description">
                                    {!! nl2br($project->description) !!}
                                </div>
                                
                                <!-- Tags -->
                                @if (count($project->tags))
                                    <div id="project-tags" class="tags">
                                        @foreach ($project->tags as $tag)
                                            <div class="tag-wrapper">
                                                <a class="tag" href="{{ route('tags.view', ['slug' => $tag->slug]) }}">
                                                    {{ $tag->name }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                            </div>
                        </div>

                        <!-- Details & tasks -->
                        <div id="project-info">
                            <div id="project-info__left">
                                
                                <!-- Details -->
                                <h3 class="content-card__small-title">@lang("tasks.view_details")</h3>
                                <div class="content-card elevation-2">
                                    <div class="content-card__content no-padding">
                                        <div id="project-details">
                                            <!-- Category -->
                                            <div class="project-detail">
                                                <div class="project-detail__key">@lang("projects.view_category")</div>
                                                <div class="project-detail__val">{{ $project->category->label }}</div>
                                            </div>
                                            <!-- Deadline -->
                                            @if ($project->has_deadline)
                                                <div class="project-detail">
                                                    <div class="project-detail__key">@lang("projects.view_deadline")</div>
                                                    <div class="project-detail__val">{{ $project->ends_at->format("d-m-Y H:m:s") }}</div>
                                                </div>
                                            @endif
                                            <!-- Budget -->
                                            @if ($project->budget > 0)
                                                <div class="project-detail">
                                                    <div class="project-detail__key">@lang("projects.view_budget")</div>
                                                    <div class="project-detail__val">&euro; {{ number_format($project->budget, 2) }}</div>
                                                </div>
                                            @endif
                                            <!-- Project code -->
                                            @if (!is_null($project->project_code))
                                                <div class="project-detail">
                                                    <div class="project-detail__key">@lang("projects.view_project_code")</div>
                                                    <div class="project-detail__val">{{ $project->project_code }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div id="project-info__right">

                                <!-- Tasks -->
                                <h3 class="content-card__small-title">@lang("projects.view_tasks")</h3>
                                <div class="content-card elevation-2">
                                    <div id="project-tasks" class="content-card__content">
                                        <div id="project-tasks__text">
                                            <div id="project-tasks__title">@lang("projects.view_tasks_title")</div>
                                            <div id="project-tasks__description">@lang("projects.view_tasks_text", ["num_tasks" => count($project->tasks)])</div>
                                        </div>
                                        <div id="project-tasks__illustration" style="background-image: url({{ asset('storage/images/undraw/to_do.svg') }});"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Share -->
                        <div id="share-project__wrapper">
                            <div id="share-project">
                                <div id="share-project__text">@lang("projects.view_share_project")</div>
                                <div id="share-project__socials">
                                    <!-- Facebook -->
                                    <div class="social-wrapper">
                                        <a class="social" href="#">
                                            <i class="fab fa-facebook-square"></i>
                                        </a>
                                    </div>
                                    <!-- Twitter -->
                                    <div class="social-wrapper">
                                        <a class="social" href="#">
                                            <i class="fab fa-twitter-square"></i>
                                        </a>
                                    </div>
                                    <!-- LinkedIn -->
                                    <div class="social-wrapper">
                                        <a class="social" href="#">
                                            <i class="fab fa-linkedin"></i>
                                        </a>
                                    </div>
                                    <!-- WhatsApp -->
                                    <div class="social-wrapper">
                                        <a class="social" href="#">
                                            <i class="fab fa-whatsapp-square"></i>
                                        </a>
                                    </div>
                                    <!-- E-mail -->
                                    <div class="social-wrapper">
                                        <a class="social" href="#">
                                            <i class="fas fa-envelope-square"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                
                </div>
            </div>

        </div>
    </div>
@stop