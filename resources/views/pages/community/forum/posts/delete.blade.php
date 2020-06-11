@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("forum.thread", $thread) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/throw_away.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content">
            <h1 id="page-header__title">@lang("forums.posts_delete_title")</h1>
            <h1 id="page-header__subtitle">{{ $thread->forum->title }}</h1>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <form action="{{ route('forum.thread.post.delete.post', ['slug' => $forum->slug, 'threadSlug' => $thread->slug, 'uuid' => $post->uuid]) }}" method="post">
                @csrf
                <div id="delete-thread-post">
                    <p>@lang("forums.posts_delete_text")</p>
                    <div class="form-controls">
                        <div class="form-controls__left">
                            <v-btn depressed href="{{ route('forum.thread', ['slug' => $forum->slug, 'threadSlug' => $thread->slug]) }}">
                                <i class="fas fa-arrow-left"></i>
                                @lang("forums.posts_delete_cancel")
                            </v-btn>
                        </div>
                        <div class="form-controls__right">
                            <v-btn depressed dark color="red" type="submit">
                                <i class="fas fa-trash-alt"></i>
                                @lang("forums.posts_delete_submit")
                            </v-btn>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

@stop