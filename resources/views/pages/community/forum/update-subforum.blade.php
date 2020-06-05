@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("forum.update-subforum", $forum) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">

            <!-- Title & subtitle -->
            <h1 id="page-header__title">@lang("forums.subforums_update_title")</h1>
            <h2 id="page-header__subtitle">{{ $forum->title }}</h2>

        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('forum.create-subforum.post', ['slug' => $forum->slug]) }}" method="post">
                @csrf
                <forum-subforum-form
                    :forum="{{ $forum->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    back-href="{{ route('forum', ['slug' => $forum->slug]) }}">
                </forum-subforum-form>
            </form>

        </div>
    </div>

@stop