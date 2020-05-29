@extends("layouts.admin")

@section("breadcrumbs")
    {!! Breadcrumbs::render("admin.users") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">@lang("admin.users_overview_title")</h1>

            @include("partials.feedback")

            <record-overview
                :records="{{ $users->toJson() }}"
                :fields="{{ $fields->toJson() }}"
                :paginated="{{ json_encode(true) }}"
                paginated-per-page="10"
                create-button-text="@lang('admin.users_overview_create_button')"
                create-button-href="{{ route('admin.users.create') }}"
                locale="{{ app()->getLocale() }}">
            </record-overview>

        </div>
    </div>
@stop