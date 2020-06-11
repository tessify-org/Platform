@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("groups.create") !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/add_file.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("groups.create_title")</h1>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Form -->
            <form action="{{ route('groups.create.post') }}" method="post" enctype="multipart/form-data">
                @csrf
                <group-form
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    :tags="{{ $tags->toJson() }}"
                    :api-endpoints="{{ $apiEndpoints->toJson() }}"
                    :default-images="{{ $defaultImages->toJson() }}"
                    back-href="{{ route('groups') }}">
                </group-form>
            </form>
            
        </div>
    </div>
    
@stop