@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("forum.thread", $thread) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">

            <!-- Title & subtitle -->
            <h1 id="page-header__title">{{ $thread->forum->title }}</h1>
            
            <!-- Back button -->
            <div id="page-header__actions">
                <div class="page-header__action">
                    <v-btn href="{{ route('forum', $thread->forum->slug) }}" color="white">
                        <i class="fas fa-arrow-left"></i>
                        @lang("forums.threads_view_back")
                    </v-btn>
                </div>
            </div>

        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Thread info -->
            <div id="view-thread__wrapper">
                <div id="view-thread" class="elevation-1">
                    <h1 id="view-thread__title">{{ $thread->title }}</h1>
                    <div id="view-thread__text">
                        {{ $thread->message }}
                    </div>
                </div>
                <div id="view-thread__footer">
                    <div id="view-thread__footer-left">
                        <user-pill :user="{{ $thread->user->toJson() }}" transparent></user-pill>
                    </div>
                    <div id="view-thread__footer-right">
                        <div class="view-thread__footer-action">
                            <v-btn color="warning" href="{{ route('forum.thread.update', ['slug' => $forum->slug, 'threadSlug' => $thread->slug]) }}">
                                <i class="fas fa-pen-square"></i>
                                @lang("general.edit")
                            </v-btn>
                        </div>
                        <div class="view-thread__footer-action">
                            <v-btn color="red" dark href="{{ route('forum.thread.delete', ['slug' => $forum->slug, 'threadSlug' => $thread->slug]) }}">
                                <i class="fas fa-trash"></i>
                                @lang("general.delete")
                            </v-btn>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reactions -->
            <div id="view-thread__reactions">
                <h2 id="view-thread__reactions-title">@lang("forums.threads_view_replies")</h2>
                @if ($thread->posts->count())
                    <div id="view-thread__reactions-list">
                        <forum-thread-posts
                            :user="{{ auth()->user()->toJson() }}"
                            :thread="{{ $thread->toJson() }}"
                            :strings="{{ $postStrings->toJson() }}">
                        </forum-thread-posts>
                    </div>
                @else
                    <div id="view-thread__no-reactions" class="elevation-1">
                        @lang("forums.threads_view_no_replies")
                    </div>
                @endif
            </div>

            <!-- Reply form -->
            <div id="view-thread__reply">
                <h2 id="view-thread__reply-title">@lang("forums.threads_view_reply")</h2>
                <form action="{{ route('forum.thread.reply.post', ['slug' => $forum->slug, 'threadSlug' => $thread->slug]) }}" method="post">
                    @csrf
                    <forum-thread-reply-form
                        :thread="{{ $thread->toJson() }}"
                        :strings="{{ $replyStrings->toJson() }}">
                    </forum-thread-reply-form>
                </form>
            </div>

        </div>
    </div>

@stop