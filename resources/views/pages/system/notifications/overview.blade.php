@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("notifications") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">@lang("notifications.title")</h1>
        
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