@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("settings") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="very-narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <!-- <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/mail_sent.svg') }}); height: 200px; top: 50px; opacity: 0.25;"></div>
            </div>
        </div> -->
        <div id="page-header__content">
            <div id="page-header__content-wrapper">

                <!-- Title -->
                <h2 id="page-header__title" class="no-margin">@lang("settings.title")</h2>
                
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

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
