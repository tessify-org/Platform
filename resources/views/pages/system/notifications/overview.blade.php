@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("notifications") !!}
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
                <h2 id="page-header__title" class="no-margin">@lang("notifications.title")</h2>
                
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">@lang("")</h1>
        
            @include("partials.feedback")
            
            <div id="notifications">
                
                <notification-overview
                    :notifications="{{ $notifications->toJson() }}"
                    no-records-text="@lang('notifications.no_records')"
                    clear-text="@lang('notifications.clear')"
                    clear-href="{{ route('notifications.clear') }}"
                    dialog-title-text="@lang('notifications.dialog_title')"
                    title-text="@lang('notifications.details_title')"
                    description-text="@lang('notifications.details_description')"
                    received-on-text="@lang('notifications.details_received_on')">
                </notification-overview>

            </div>

        </div>
    </div>

@stop