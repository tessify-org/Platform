@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("tasks.view", $task) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="white-border-bottom">
        <div id="page-header__bg" style="background-image: url({{ asset($task->header_image_url) }})"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title">{{ $task->title }}</h1>
                @if ($task->project)
                    <h2 id="page-header__subtitle" class="no-margin">
                        {!! __("tasks.view_part_of_project", ["title" => $task->project->title]) !!}
                    </h2>
                @endif
            </div>
        </div>
        <div id="page-header__actions-wrapper">
            <div id="page-header__actions" class="align-right">
                @if (!auth()->user()->hasSubscribed($task))
                    <v-btn color="white" href="{{ route('tasks.subscribe', ['slug' => $task->slug]) }}">
                        <i class="fas fa-check-circle"></i>
                        @lang("tasks.view_subscribe")
                    </v-btn>
                @else
                    <v-btn color="white" href="{{ route('tasks.unsubscribe', ['slug' => $task->slug]) }}">
                        <i class="fas fa-times-circle"></i>
                        @lang("tasks.view_unsubscribe")
                    </v-btn>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback", ["extraMargin" => true])

            <!-- Task page -->
            <div id="view-task">
                <aside id="view-task__sidebar">

                    <!-- Sidebar -->
                    @include("partials.tasks.view-sidebar", [
                        "task" => $task,
                        "users" => $users,
                    ])

                </aside>
                <main id="view-task__content">

                    <!-- Progress report -->
                    @if ($task->status->name != "completed" && $task->assigned_to_user)
                        <h3 class="content-card__small-title">@lang("tasks.view_awaiting_progress")</h3>
                        <div id="task__report-progress" class="elevation-2">
                            <div id="report-progress__title">@lang("tasks.view_awaiting_progress_title")</div>
                            <div id="report-progress__description">@lang("tasks.view_awaiting_progress_text")</div>
                            <div id="report-progress__actions">
                                <v-btn depressed color="white" href="{{ route('tasks.report-progress', $task->slug) }}">
                                    <i class="fas fa-parachute-box"></i>
                                    @lang("tasks.view_awaiting_progress_button")
                                </v-btn>
                            </div>
                            <div id="report-progress__illustration" style="background-image: url({{ asset('storage/images/undraw/gift_white.svg') }})"></div>
                        </div>
                    @endif
                    
                    <!-- Review -->
                    @if ($userHasPendingReviewRequest)
                        <div id="task__review" class="elevation-2">
                            <div id="task__review-title">@lang("tasks.view_pending_review_title")</div>
                            <div id="task__review-text">@lang("tasks.view_pending_review_text")</div>
                            <div id="task__review-actions">
                                <v-btn color="primary" href="{{ route('reviews.create', ['type' => 'task', 'slug' => $task->slug]) }}" depressed>
                                    <i class="fas fa-feather-alt"></i>
                                    @lang("tasks.view_pending_review_button")
                                </v-btn>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Sign up -->
                    @if ($task->status->name != "completed" && $task->has_available_slots && !$task->assigned_to_user)
                        <h3 class="content-card__small-title">@lang("tasks.view_sign_up")</h3>
                        <div id="task__sign-up" class="content-card elevation-2">
                            <div id="sign-up__text">
                                <div id="sign-up__title">@lang("tasks.view_sign_up_title")</div>
                                <div id="sign-up__description">@lang("tasks.view_sign_up_description")</div>
                                <div id="sign-up__actions">
                                    <v-btn depressed color="white" href="{{ route('tasks.assign-to-me', $task->slug) }}">
                                        <i class="far fa-thumbs-up"></i>
                                        @lang("tasks.view_sign_up_button")
                                    </v-btn>
                                </div>
                            </div>
                            <div id="sign-up__illustration" style="background-image: url({{ asset('storage/images/undraw/freelancer_white.svg') }});"></div>
                        </div>
                    @endif

                    <!-- Task information -->
                    <div id="task">

                        <!-- Description & Tags -->
                        <h3 class="content-card__small-title">@lang("tasks.view_description")</h3>
                        <div class="content-card elevation-2 mb">
                            <div class="content-card__content">

                                <!-- Description -->
                                <div id="task-description">
                                    {!! nl2br($task->description) !!}
                                </div>
                                
                                <!-- Tags -->
                                @if (count($task->tags))
                                    <div id="task-tags" class="tags">
                                        @foreach ($task->tags as $tag)
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

                        <!-- Details & location -->
                        <div id="task-info">
                            <div id="task-info__left">
                                
                                <!-- Details -->
                                <h3 class="content-card__small-title">@lang("tasks.view_details")</h3>
                                <div class="content-card elevation-2">
                                    <div class="content-card__content no-padding">
                                        <div id="task-details">
                                            <!-- Complexity -->
                                            <div class="task-detail">
                                                <div class="task-detail__key">@lang("tasks.view_complexity")</div>
                                                <div class="task-detail__val">{{ $task->complexity }}/10</div>
                                            </div>
                                            <!-- Estimated hours --> 
                                            <div class="task-detail">
                                                <div class="task-detail__key">@lang("tasks.view_estimated_hours")</div>
                                                <div class="task-detail__val">{{ $task->estimated_hours }} @lang("tasks.view_hours")</div>
                                            </div>
                                            @if ($task->status->name == "completed")
                                                <div class="task-detail">
                                                    <div class="task-detail__key">@lang("tasks.view_realized_hours")</div>
                                                    <div class="task-detail__val">{{ $task->realized_hours }} @lang("tasks.view_hours")</div>
                                                </div>
                                            @endif
                                            <!-- Number of available positions -->
                                            <div class="task-detail">
                                                <div class="task-detail__key">@lang("tasks.view_number_positions")</div>
                                                @if ($task->num_positions == 1)
                                                    <div class="task-detail__val">1 @lang("tasks.view_position")</div>
                                                @else
                                                    <div class="task-detail__val">{{ $task->num_positions }} @lang("tasks.view_positions")</div>
                                                @endif
                                            </div>
                                            <!-- Urgency -->
                                            <div class="task-detail">
                                                <div class="task-detail__key">@lang("tasks.view_urgency")</div>
                                                <div class="task-detail__val">
                                                    @if ($task->urgency == 1)
                                                        @lang("general.urgency_low")
                                                    @elseif ($task->urgency == 2)
                                                        @lang("general.urgency_normal")
                                                    @elseif ($task->urgency == 3)
                                                        @lang("general.urgency_high")
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div id="task-info__right">

                                <!-- Location -->
                                <h3 class="content-card__small-title">@lang("tasks.view_location")</h3>
                                <div class="content-card elevation-2">
                                    <div id="task-location" class="content-card__content">
                                        <div id="task-location__text">
                                            <div id="task-location__title">@lang("tasks.view_remote_work_title")</div>
                                            <div id="task-location__description">@lang("tasks.view_remote_work_description")</div>
                                        </div>
                                        <div id="task-location__illustration" style="background-image: url({{ asset('storage/images/undraw/working_remotely.svg') }});"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        <!-- Required skills -->
                        @if (count($task->skills))
                            <h3 class="content-card__small-title">@lang("tasks.view_skills")</h3>
                            <div class="content-card elevation-2">
                                <div class="content-card__content no-padding">
                                    
                                    <!-- Required skill list -->
                                    <task-required-skills-accordeon
                                        :skills="{{ json_encode($task->skills) }}"
                                        :strings="{{ $requiredSkillsStrings->toJson() }}"
                                        locale="{{ $locale }}">
                                    </task-required-skills-accordeon>

                                </div>
                            </div>
                        @endif

                        <!-- Share -->
                        <div id="share-task__wrapper">
                            <div id="share-task">
                                <div id="share-task__text">@lang("tasks.view_share_task")</div>
                                <div id="share-task__socials">
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

                </main>
            </div>

        </div>
    </div>

@stop