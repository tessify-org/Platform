@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("forum.delete-subforum", $forum) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">

            <!-- Title & subtitle -->
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
            <form action="{{ route('forum.delete-subforum.post', ['slug' => $forum->slug]) }}" method="post">
                @csrf
                <div id="delete-subforum">
                    <p>@lang("forums.subforums_delete_text")</p>
                    <div class="form-controls">
                        <div class="form-controls__left">
                            <v-btn depressed href="{{ route('forum', ['slug' => $forum->slug]) }}">
                                <i class="fas fa-arrow-left"></i>
                                @lang("forums.subforums_delete_cancel")
                            </v-btn>
                        </div>
                        <div class="form-controls__right">
                            <v-btn depressed dark color="red" type="submit">
                                <i class="fas fa-trash-alt"></i>
                                @lang("forums.subforums_delete_submit")
                            </v-btn>
                        </div>
                    </div>
                </div>
            </form>


        </div>
    </div>

@stop