@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("settings") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Page title -->
            <h1 class="page-title centered">@lang("settings.title")</h1>

            <!-- Setting links -->
            <div id="setting-links" class="elevation-1">
                <a class="setting-link" href="{{ route('settings.change-password') }}">
                    <span class="setting-link__icon">
                        <i class="fas fa-key"></i>
                    </span>
                    <span class="setting-link__text">@lang("settings.link_change_password")</span>
                </a>
            </div>

        </div>
    </div>
@stop
