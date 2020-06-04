@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("forum", $forum) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">

            <!-- Title & subtitle -->
            <h1 id="page-header__title">@lang("forums.threads_create_title")</h1>
            <h2 id="page-header__subtitle">{{ $forum->title }}</h2>

        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('forum.create-thread.post', ['slug' => $forum->slug]) }}" method="post">
                @csrf
                <forum-thread-form
                    :strings="{{ $strings->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    back-href="{{ route('forum', ['slug' => $forum->slug]) }}">
                </forum-thread-form>
            </form>

        </div>
    </div>

@stop