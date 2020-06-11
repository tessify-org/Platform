@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("poll.edit", $poll) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/engineers.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("polls.edit_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">
            
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('poll.edit.post', $poll->slug) }}" method="post" enctype="multipart/form-data">
                @csrf
                <poll-form
                    :poll="{{ $poll->toJson() }}"
                    :groups="{{ $myGroups->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    back-href="{{ route('poll', $poll->slug) }}">
                </poll-form>
            </form>
            
        </div>
    </div>

@stop