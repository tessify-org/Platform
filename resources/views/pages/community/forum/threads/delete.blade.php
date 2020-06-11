@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("forum.thread.delete", $thread) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__content">
            <h1 id="page-header__title">@lang("forums.threads_delete_title")</h1>
            <h1 id="page-header__subtitle">{{ $thread->title }}</h1>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <form action="{{ route('forum.thread.delete.post', ['slug' => $forum->slug, 'threadSlug' => $thread->slug]) }}" method="post">
                @csrf
                <div id="delete-thread-post">
                    <p>@lang("forums.threads_delete_text")</p>
                    <div class="form-controls">
                        <div class="form-controls__left">
                            <v-btn depressed href="{{ route('forum.thread', ['slug' => $forum->slug, 'threadSlug' => $thread->slug]) }}">
                                <i class="fas fa-arrow-left"></i>
                                @lang("forums.threads_delete_cancel")
                            </v-btn>
                        </div>
                        <div class="form-controls__right">
                            <v-btn depressed dark color="red" type="submit">
                                <i class="fas fa-trash-alt"></i>
                                @lang("forums.threads_delete_submit")
                            </v-btn>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

@stop