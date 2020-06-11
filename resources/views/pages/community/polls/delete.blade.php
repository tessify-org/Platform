@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("poll.delete", $poll) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/throw_away.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("polls.delete_title")</h1>
            </div>
        </div>
    </div>
    
    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">
            
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <div class="elevation-1" id="delete-poll">
                <form action="{{ route('poll.delete.post', $poll->slug) }}" method="post">
                    @csrf
                    <p>@lang("polls.delete_text")</p>
                    <div class="form-controls">
                        <div class="form-controls__left">
                            <v-btn depressed href="{{ route('poll', $poll->slug) }}">
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
    </div>

@stop