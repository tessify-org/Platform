@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("polls.create") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title & subtitle -->
            <h1 class="page-title centered">@lang("polls.create_title")</h1>
            
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('polls.create.post') }}" method="post" enctype="multipart/form-data">
                @csrf
                <poll-form
                    :strings="{{ $strings->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    default-header-image-url="{{ asset('storage/images/polls/default.jpg') }}">
                </poll-form>
            </form>
            
        </div>
    </div>
@stop