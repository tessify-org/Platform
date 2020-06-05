@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("group.forum.update-thread", $group, $forum, $thread) !!}
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
                                <h2 id="group-subtitle">{{ $thread->title }}</h2>
                            </div>
                            <div id="group-header__actions">
                                <div id="group-header__action-left">
                                    <!-- Back to parent forum -->
                                    <v-btn small depressed href="{{ route('group.forum', ['slug' => $group->slug, 'forumSlug' => $forum->slug]) }}">
                                        <i class="fas fa-arrow-left"></i>
                                        @lang("groups.forum_overview_back_to_parent")
                                    </v-btn>
                                </div>
                                <div id="group-header__actions-right">
                                    <!-- Update thread -->
                                    <v-btn small depressed color="warning" href="{{ route('group.forum.update-thread', ['slug' => $group->slug, 'forumSlug' => $forum->slug, 'threadSlug' => $thread->slug]) }}">
                                        <i class="fas fa-edit"></i>
                                        @lang("groups.forum_thread_edit")
                                    </v-btn>
                                    <!-- Delete thread -->
                                    <v-btn small depressed dark color="red" href="{{ route('group.forum.delete-thread', ['slug' => $group->slug, 'forumSlug' => $forum->slug, 'threadSlug' => $thread->slug]) }}">
                                        <i class="fas fa-trash-alt"></i>
                                        @lang("groups.forum_thread_delete")
                                    </v-btn>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div id="group-content">

                            <!-- Thread -->
                            <div id="group-forum-thread">
                                <div id="group-forum-thread__text">
                                    {{ $thread->message }}
                                </div>
                                <div id="group-forum-thread__footer">
                                    <div id="group-forum-thread__footer-left">
                                        <user-pill :user="{{ $thread->user->toJson() }}" transparent smaller></user-pill>
                                    </div>
                                    <div id="group-forum-thread__footer-right">
                                        {{ $thread->created_at->format("d-m-Y H:m:s") }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reactions -->
                            <div id="view-thread__reactions">
                                <h2 id="view-thread__reactions-title">@lang("groups.forum_thread_replies")</h2>
                                @if ($thread->posts->count())
                                    <div id="view-thread__reactions-list">
                                        <forum-thread-posts
                                            :user="{{ auth()->user()->toJson() }}"
                                            :thread="{{ $thread->toJson() }}"
                                            :strings="{{ $postStrings->toJson() }}">
                                        </forum-thread-posts>
                                    </div>
                                @else
                                    <div id="view-thread__no-reactions">
                                        @lang("groups.forum_thread_no_replies")
                                    </div>
                                @endif
                            </div>

                            <!-- Reply form -->
                            <div id="view-thread__reply">
                                <h2 id="view-thread__reply-title">@lang("groups.forum_thread_reply")</h2>
                                <form action="{{ route('group.forum.reply-to-thread.post', ['slug' => $group->slug, 'forumSlug' => $forum->slug, 'threadSlug' => $thread->slug]) }}" method="post">
                                    @csrf
                                    <forum-thread-reply-form
                                        :group="{{ $group->toJson() }}"
                                        :thread="{{ $thread->toJson() }}"
                                        :strings="{{ $replyStrings->toJson() }}">
                                    </forum-thread-reply-form>
                                </form>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
@stop