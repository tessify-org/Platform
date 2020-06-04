@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("forum.thread.update", $thread) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">

            <!-- Title & subtitle -->
            <h1 id="page-header__title">{{ $thread->forum->title }}</h1>
            <h2 id="page-header__subtitle">@lang("forums.threads_update_title")</h2>
            
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('forum.thread.update.post', ['slug' => $thread->forum->slug, 'threadSlug' => $thread->slug]) }}" method="post">
                @csrf

                <forum-thread-form
                    :thread="{{ $thread->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    back-href="{{ route('forum.thread', ['slug' => $thread->forum->slug, 'threadSlug' => $thread->slug]) }}">
                </forum-thread-form>

            </form>

        </div>
    </div>

@stop