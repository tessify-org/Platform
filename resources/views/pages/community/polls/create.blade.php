@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("polls.create") !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/add_file.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("polls.create_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">
            
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('polls.create.post') }}" method="post" enctype="multipart/form-data">
                @csrf
                <poll-form
                    :strings="{{ $strings->toJson() }}"
                    :groups="{{ $myGroups->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    default-header-image-url="{{ asset('storage/images/polls/default.jpg') }}">
                </poll-form>
            </form>
            
        </div>
    </div>

@stop