@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("messages.send") !!}
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
                <h2 id="page-header__subtitle" class="no-margin">@lang("messages.send_title")</h2>
                
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
                        "page" => "send",
                    ])
                </div>
                <div id="messages-content__wrapper">
                    <div id="messages-content" class="elevation-1">
                        
                        <form action="{{ route('messages.send.post') }}" method="post">
                            @csrf

                            <send-message-form
                                :users="{{ $users->toJson() }}"
                                :errors="{{ $errors->toJson() }}"
                                :old-input="{{ $oldInput->toJson() }}"
                                :reply-to="{{ !is_null($replyTo) ? $replyTo->toJson() : json_encode(null) }}"
                                user-text="@lang('messages.send_user')"
                                subject-text="@lang('messages.send_subject')"
                                message-text="@lang('messages.send_message')"
                                back-href="{{ route('messages') }}"
                                back-text="@lang('messages.send_back')"
                                submit-text="@lang('messages.send_submit')">
                            </send-message-form>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

@stop