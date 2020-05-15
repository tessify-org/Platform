@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("auth.forgot-password") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <div id="forgot-password">

                <h1 class="page-title centered">@lang('auth.forgot_password_title')</h1>
            
                @include("partials.feedback")

                <form action="{{ route('auth.forgot-password.post') }}" method="post">
                    @csrf

                    <forgot-password-form
                        email-text="@lang('auth.forgot_password_email')"
                        submit-text="@lang('auth.forgot_password_submit')"
                        back-text="@lang('auth.forgot_password_back')"
                        back-href="{{ route('auth.login') }}"
                        :errors="{{ $errors->toJson() }}">
                    </forgot-password-form>

                </form>

            </div>
            
        </div>
    </div>
@stop