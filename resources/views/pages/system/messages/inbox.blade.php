@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("messages") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="very-narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/mailbox.svg') }}); width: 130px; height: 130px; top: 36px;"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">

                <!-- Title & subtitle -->
                <h1 id="page-header__title">@lang("messages.title")</h1>
                <h2 id="page-header__subtitle" class="no-margin">@lang("messages.inbox_title")</h2>
                
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">
            
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Messages -->
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