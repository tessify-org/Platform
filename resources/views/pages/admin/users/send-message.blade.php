@extends("layouts.admin")

@section("breadcrumbs")
    {!! Breadcrumbs::render("admin.users.send-message", $user) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">@lang("admin.users_send_message_title")</h1>

            @include("partials.feedback")

            <form action="{{ route('admin.users.send-message.post', $user->id) }}" method="post">
                @csrf

                <div class="content-card elevation-1">
                    <div class="content-card__content">
                        
                        <admin-send-message-form
                            :user="{{ $user->toJson() }}"
                            :strings="{{ $strings->toJson() }}"
                            :errors="{{ $errors->toJson() }}"
                            :old-input="{{ $oldInput->toJson() }}">
                        </admin-send-message-form>

                    </div>

                    <div class="content-card__footer">
                        <div class="content-card__footer-left">
                            <v-btn text depressed href="{{ route('admin.users.view', $user->id) }}">
                                <i class="fas fa-arrow-left"></i>
                                @lang("admin.users_send_message_back")
                            </v-btn>
                        </div>
                        <div class="content-card__footer-right">
                            <v-btn dark depressed color="green" type="submit">
                                <i class="fas fa-paper-plane"></i>
                                @lang("admin.users_send_message_submit")
                            </v-btn>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
@stop