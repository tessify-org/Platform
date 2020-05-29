@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("poll.edit", $poll) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title & subtitle -->
            <h1 class="page-title centered">@lang("polls.edit_title")</h1>
            
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('poll.edit.post', $poll->slug) }}" method="post">
                @csrf
                <poll-form
                    :poll="{{ $poll->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}">
                </poll-form>
            </form>
            
        </div>
    </div>
@stop