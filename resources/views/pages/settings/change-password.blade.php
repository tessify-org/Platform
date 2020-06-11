@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("settings.change-password") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="very-narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__content">
            <div id="page-header__content-wrapper">
                <h2 id="page-header__title" class="no-margin">@lang("settings.change_password_title")</h2>
            </div>
        </div>
    </div>

    <!-- Content wrapper -->
    <div class="content-section__wrapper">
        <div class="content-section">
            
            <!-- Form -->
            <form action="{{ route('settings.change-password.post') }}" method="post">
                @csrf

                <!-- Change password wrapper -->
                <div id="change-password-wrapper">

                    <!-- Feedback -->
                    @include("partials.feedback")

                    <!-- Change password form -->
                    <change-password-form
                        :errors="{{ $errors->toJson() }}"
                        :strings="{{ $strings->toJson() }}">
                    </change-password-form>

                </div>

            </form>

        </div>
    </div>
@stop
