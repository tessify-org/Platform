@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("settings.change-password") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/my_password.svg') }});"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h2 id="page-header__title" class="no-margin">@lang("settings.change_password_title")</h2>
            </div>
        </div>
    </div>

    <!-- Content wrapper -->
    <div class="content-section__wrapper">
        <div class="content-section">
            
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Change password -->
            <div id="change-password-wrapper">
                <div id="change-password__form">

                    <!-- Form -->
                    <form action="{{ route('settings.change-password.post') }}" method="post">
                        @csrf
                        <change-password-form
                            :errors="{{ $errors->toJson() }}"
                            :strings="{{ $strings->toJson() }}"
                            back-href="{{ route('settings') }}">
                        </change-password-form>
                    </form>

                </div>
            </div>

        </div>
    </div>
@stop
