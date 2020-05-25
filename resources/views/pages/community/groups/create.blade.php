@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("groups.create") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg" style="background-image: url({{ asset('storage/images/groups/headers/default.jpg') }})"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">

            <!-- Title & subtitle -->
            <h1 id="page-header__title" class="no-margin">@lang("groups.create_title")</h1>

        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

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