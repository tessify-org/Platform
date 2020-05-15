@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("memberlist") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title -->
            <h1 id="memberlist-title" class="page-title centered">
                @lang("profiles.memberlist_title")
            </h1>

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Memberlist -->
            <memberlist
                :users="{{ $users->toJson() }}"
                :ministries="{{ $ministries->toJson() }}"
                :organizations="{{ $organizations->toJson() }}"
                name-text="@lang('profiles.memberlist_name')"
                reputation-text="@lang('profiles.memberlist_reputation')"
                no-records-text="@lang('profiles.memberlist_no_users')"
                points-text="@lang('profiles.memberlist_points')"
                search-text="@lang('profiles.memberlist_search')"
                ministry-text="@lang('profiles.memberlist_ministry')"
                organization-text="@lang('profiles.memberlist_organization')">
            </memberlist>

        </div>
    </div>
@stop