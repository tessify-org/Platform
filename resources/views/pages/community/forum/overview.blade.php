@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("forum", $forum) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/online_discussion.svg') }});"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">

                <!-- Title & subtitle -->
                @if ($forum->hasParent())
                    <h1 id="page-header__title">{{ $forum->parentForum->title }}</h1>
                    <h2 id="page-header__subtitle">{{ $forum->title }}</h2>
                @else
                    <h1 id="page-header__title" class="no-margin">{{ $forum->title }}</h1>
                @endif

                <!-- Actions -->
                <div id="page-header__actions">
                    <!-- Create subforum -->
                    <div class="page-header__action">
                        <v-btn color="primary" href="{{ route('forum.create-subforum', $forum->slug) }}">
                            <i class="fas fa-plus"></i>
                            @lang("forums.general_overview_create_subforum")
                        </v-btn>
                    </div>
                    @if ($forum->hasParent())
                        <!-- Edit -->
                        <div class="page-header__action">
                            <v-btn color="warning" href="{{ route('forum.update-subforum', $forum->slug) }}" class="icon-only">
                                <i class="fas fa-edit"></i>
                            </v-btn>
                        </div>
                        <!-- Delete -->
                        <div class="page-header__action">
                            <v-btn color="red" dark href="{{ route('forum.delete-subforum', $forum->slug) }}" class="icon-only">
                                <i class="fas fa-trash"></i>
                            </v-btn>
                        </div>
                    @endif
                </div>
                
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Forum -->
            <div id="forum-overview">

                <!-- Subforums -->
                @if ($forum->subforums->count())
                    <div id="forum-overview__subforums">
                        <h2 id="forum-overview__subforums-title">@lang("forums.overview_subforums")</h2>
                        <div id="forum-overview__subforums-list" class="elevation-1">
                            @foreach ($forum->subforums as $subforum)
                                <a href="{{ route('forum', $subforum->slug) }}" class="subforums-list__entry">
                                    <span class="subforum-text">
                                        <span class="subforum-title">{{ $subforum->title }}</span>
                                        <span class="subforum-description">{{ $subforum->description }}</span>
                                    </span>
                                    <span class="subforum-created-at">{{ $subforum->created_at }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Threads -->
                <div id="forum-overview__threads">
                    <div id="forum-overview__threads-header">
                        <h2 id="forum-overview__threads-title">@lang("forums.overview_threads")</h2>
                        <div id="forum-overview__threads-actions">
                            <v-btn small color="primary" href="{{ route('forum.create-thread', $forum->slug) }}">
                                <i class="fas fa-plus"></i>
                                @lang("forums.general_overview_create_thread")
                            </v-btn>
                        </div>
                    </div>
                    @if ($threads->count())
                        <div id="forum-overview__threads-list" class="elevation-1">
                            @foreach ($threads as $thread)
                                <a href="{{ route('forum.thread', ['slug' => $forum->slug, 'threadSlug' => $thread->slug]) }}" class="threads-list__entry">
                                    @if ($thread->closed)
                                        <span class="thread-icon">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    @endif
                                    @if ($thread->sticky)
                                        <span class="thread-icon">
                                            <i class="fas fa-thumbtack"></i>
                                        </span>
                                    @endif
                                    <span class="thread-title">{{ $thread->title }}</span>
                                    <span class="thread-created-at">{{ $thread->created_at }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div id="forum-overview__no-threads" class="elevation-1">
                            @lang("forums.overview_no_threads")
                        </div>
                    @endif
                </div>

            </div>
            
        </div>
    </div>

@stop