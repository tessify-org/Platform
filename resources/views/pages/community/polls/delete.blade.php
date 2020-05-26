@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("poll.delete", $poll) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title & subtitle -->
            <h1 class="page-title centered">@lang("polls.delete_title")</h1>
            
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('poll.delete.post', $poll->slug) }}" method="post">
                @csrf

                <p>@lang("polls.delete_text")</p>

                <div class="form-controls">
                    <div class="form-controls__left">
                        <v-btn depressed href="{{ route('poll', $poll->slug }}">
                            <i class="fas fa-arrow-left"></i>
                            @lang("polls.delete_cancel")
                        </v-btn>
                    </div>
                    <div class="form-controls__right">
                        <v-btn depressed dark color="red" type="submit">
                            <i class="fas fa-trash-alt"></i>
                            @lang("polls.delete_submit")
                        </v-btn>
                    </div>
                </div>

            </form>
            
        </div>
    </div>
@stop