@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("group.forum", $group, $forum) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- View group -->
            <div id="view-group">
                <div id="view-group__sidebar">
                    
                    <!-- Sidebar -->
                    @include("partials.groups.view-sidebar", [
                        "group" => $group,
                        "page" => "forum",
                    ])
                    
                </div>
                <div id="view-group__content">

                    <!-- Group -->
                    <div id="group" class="elevation-2">

                        <!-- Group header -->
                        <div id="group-header">
                            <div id="group-header__bg" style="background-image: url({{ asset($group->header_image_url) }})"></div>
                            <div id="group-header__bg-overlay"></div>
                            <div id="group-header__text">
                                <h1 id="group-title">{{ $group->name }}</h1>
                                @if ($forum->hasParent())
                                    <h2 id="group-subtitle">{{ $forum->title }}</h2>
                                @else
                                    <h2 id="group-subtitle">@lang("groups.forum_title")</h2>
                                @endif
                            </div>
                            <div id="group-header__actions">
                                @if ($forum->parentForum)
                                    <div id="group-header__action-left">
                                        <!-- Back to parent forum -->
                                        <v-btn small depressed href="{{ route('group.forum', ['slug' => $group->slug, 'forumSlug' => $forum->parentForum->slug]) }}">
                                            <i class="fas fa-arrow-left"></i>
                                            @lang("groups.forum_overview_back_to_parent")
                                        </v-btn>
                                    </div>
                                @endif
                                <div id="group-header__actions-right">
                                    <!-- Create thread -->
                                    <v-btn small depressed color="primary" href="{{ route('group.forum.create-thread', ['slug' => $group->slug, 'forumSlug' => $forum->slug]) }}">
                                        <i class="fas fa-plus"></i>
                                        @lang("groups.forum_overview_add_thread")
                                    </v-btn>
                                    <!-- Create subforum -->
                                    <v-btn small depressed color="primary" href="{{ route('group.forum.create-subforum', ['slug' => $group->slug, 'forumSlug' => $forum->slug]) }}">
                                        <i class="fas fa-plus"></i>
                                        @lang("groups.forum_overview_add_subforum")
                                    </v-btn>
                                    @if ($forum->parentForum)
                                        <!-- Edit -->
                                        <v-btn small depressed class="icon-only" color="warning" href="{{ route('group.forum.update-subforum', ['slug' => $group->slug, 'forumSlug' => $forum->slug]) }}">
                                            <i class="fas fa-edit"></i>
                                        </v-btn>
                                        <!-- Delete -->
                                        <v-btn dark small depressed class="icon-only" color="red" href="{{ route('group.forum.delete-subforum', ['slug' => $group->slug, 'forumSlug' => $forum->slug]) }}">
                                            <i class="fas fa-trash"></i>
                                        </v-btn>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div id="group-content">

                            <!-- Subforums -->
                            @if (count($forum->subforums))
                                <div id="forum-overview__subforums">
                                    <h2 id="forum-overview__subforums-title">@lang("groups.forum_overview_subforums")</h2>
                                    <div id="forum-overview__subforums-list">
                                        @foreach ($forum->subforums as $subforum)
                                            <a href="{{ route('group.forum', ['slug' => $group->slug, 'forumSlug' => $subforum->slug]) }}" class="subforums-list__entry">
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
                                <h2 id="forum-overview__threads-title">@lang("groups.forum_overview_threads")</h2>
                                @if (count($forum->threads))
                                    <div id="forum-overview__threads-list">
                                        @foreach ($forum->threads as $thread)
                                            <a href="{{ route('group.forum.thread', ['slug' => $group->slug, 'forumSlug' => $forum->slug, 'threadSlug' => $thread->slug]) }}" class="threads-list__entry">
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
                                    <div id="forum-overview__no-threads">
                                        @lang("groups.forum_overview_no_threads")
                                    </div>
                                @endif
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
@stop