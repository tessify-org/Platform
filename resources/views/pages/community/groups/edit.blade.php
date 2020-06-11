@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("group.edit", $group) !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/engineers.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin">@lang("groups.update_title")</h1>
            </div>
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