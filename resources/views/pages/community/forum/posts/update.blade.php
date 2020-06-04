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
            <h1 id="page-header__title">@lang("forums.posts_update_title")</h1>
            <h1 id="page-header__subtitle">{{ $thread->forum->title }}</h1>
            
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <form action="{{ route('forum.thread.post.update', ['slug' => $forum->slug, 'threadSlug' => $thread->slug, 'uuid' => $post->uuid]) }}" method="post">
                @csrf

                <forum-post-update-form
                    :post="{{ $post->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    :errors="{{ $errors->toJson() }}">
                </forum-post-update-form>

            </form>

        </div>
    </div>

@stop