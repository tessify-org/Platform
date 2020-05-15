@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("messages.outbox") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">@lang("messages.title")</h1>
            <h2 class="page-subtitle centered">@lang("messages.outbox_title")</h2>
        
            @include("partials.feedback")
            
            <div id="messages">
                <div id="messages-sidebar__wrapper">
                    @include("partials.messages-navigation", [
                        "page" => "outbox",
                    ])
                </div>
                <div id="messages-content__wrapper">
                    <messages-outbox
                        :messages="{{ $messages->toJson() }}"
                        no-records-text="@lang('messages.outbox_empty')">
                    </messages-outbox>
                </div>
            </div>

        </div>
    </div>
@stop