@extends("layouts.admin")

@section("breadcrumbs")
    {!! Breadcrumbs::render("admin.users.ban", $user) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">@lang("admin.users_ban_title")</h1>

            @include("partials.feedback")

            <form action="{{ route('admin.users.ban.post', $user->id) }}" method="post">
                @csrf

                <div id="ban-dialog" class="content-card elevation-1">
                    <div class="content-card__content">
                        <ban-form
                            :user="{{ $user->toJson() }}"
                            :errors="{{ $errors->toJson() }}"
                            :old-input="{{ $oldInput->toJson() }}"
                            user-text="@lang('admin.users_ban_user')"
                            type-text="@lang('admin.users_ban_type')"
                            duration-text="@lang('admin.users_ban_duration')"
                            temporary-text="@lang('admin.users_ban_temporary')"
                            permanent-text="@lang('admin.users_ban_permanent')">
                        </ban-form>
                    </div>
                    <div class="content-card__footer">
                        <div class="content-card__footer-left">
                            <v-btn depressed flat href="{{ route('admin.users.view', $user->id) }}">
                                <i class="fas fa-arrow-left"></i>
                                @lang("general.go_back")
                            </v-btn>
                        </div>
                        <div class="content-card__footer-right">
                            <v-btn dark depressed color="red" type="submit">
                                <i class="fas fa-gavel"></i>
                                @lang("admin.users_ban_submit")
                            </v-btn>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
@stop