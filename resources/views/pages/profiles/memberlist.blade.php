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
                :strings="{{ $strings->toJson() }}"
                locale="{{ app()->getLocale() }}">
            </memberlist>

        </div>
    </div>
@stop