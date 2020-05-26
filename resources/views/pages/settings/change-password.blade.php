@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("settings.change-password") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title -->
            <h1 class="page-title centered">@lang("settings.change_password_title")</h1>
            
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
