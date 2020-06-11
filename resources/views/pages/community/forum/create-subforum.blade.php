@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("forum.create-subforum", $forum) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/add_file.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content">
            <h1 id="page-header__title">@lang("forums.subforums_create_title")</h1>
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