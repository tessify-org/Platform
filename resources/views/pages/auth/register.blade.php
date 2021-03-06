@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("auth.register") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <div id="register">

                <h1 class="page-title centered">@lang('auth.register_title')</h1>
            
                @include("partials.feedback")

                <form action="{{ route('auth.register.post') }}" method="post">
                    @csrf

                    <register-form
                        :errors="{{ $errors->toJson() }}"
                        :old-input="{{ $oldInput->toJson() }}"
                        annotation-text="@lang('auth.register_annotation')"
                        first-name-text="@lang('auth.register_first_name')"
                        last-name-text="@lang('auth.register_last_name')"
                        email-text="@lang('auth.register_email')"
                        password-text="@lang('auth.register_password')"
                        confirm-password-text="@lang('auth.register_confirm_password')"
                        submit-text="@lang('auth.register_submit')"
                        login-text="@lang('auth.register_go_to_login')"
                        login-href="{{ route('auth.login') }}">
                    </register-form>
                
                </form>

            </div>

        </div>
    </div>
@stop