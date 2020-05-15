@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("messages") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">@lang("messages.title")</h1>
            <h2 class="page-subtitle centered">@lang("messages.inbox_title")</h2>
        
            @include("partials.feedback")
            
            <div id="messages">
                <div id="messages-sidebar__wrapper">
                    @include("partials.messages-navigation", [
                        "page" => "inbox",
                    ])
                </div>
                <div id="messages-content__wrapper">
                    <messages-inbox
                        :messages="{{ $messages->toJson() }}"
                        no-records-text="@lang('messages.inbox_empty')">
                    </messages-inbox>
                </div>
            </div>

        </div>
    </div>
@stop