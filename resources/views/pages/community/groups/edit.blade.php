@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("group.edit", $group) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg" style="background-image: url({{ asset('storage/images/groups/headers/default.jpg') }})"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">

            <!-- Title & subtitle -->
            <h1 id="page-header__title" class="no-margin">@lang("groups.update_title")</h1>

        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <form action="{{ route('group.edit.post', $group->slug) }}" method="post" enctype="multipart/form-data">
                @csrf

                <group-form
                    :group="{{ $group->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    back-href="{{ route('groups') }}"
                    :tags="{{ $tags->toJson() }}">
                </group-form>

            </form>
            
        </div>
    </div>
    
@stop