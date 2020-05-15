@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("auth.reset-password", ["code" => $code, "email" => $email]) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <div id="reset-password">

                <h1 class="page-title centered">@lang('auth.reset_password_title')</h1>
            
                @include("partials.feedback")

                <form action="{{ route('auth.reset-password.post', ['email' => $email, 'code' => $code]) }}" method="post">
                    @csrf

                    <reset-password-form
                        code="{{ $code }}"
                        email="{{ $email }}"
                        :errors="{{ $errors->toJson() }}"
                        email-label-text="@('auth.reset_password_form_email')"
                        code-label-text="@('auth.reset_password_form_code')"
                        password-label-text="@('auth.reset_password_form_password')"
                        password-confirmation-label-text="@('auth.reset_password_form_password_confirmation')"
                        back-href="{{ route('auth.login') }}"
                        back-button-text="@('auth.reset_password_form_back')"
                        submit-button-text="@('auth.reset_password_form_submit')">
                    </reset-password-form>

                </form>

            </div>

        </div>
    </div>
@stop