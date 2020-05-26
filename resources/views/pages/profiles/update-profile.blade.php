@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("profile.update", $user) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Page title -->
            <h1 class="page-title centered">@lang("profiles.update_profile_title")</h1>

            <!-- Feedback -->
            @include("partials.feedback")

            <form action="{{ route('profile.update.post') }}" method="post" enctype="multipart/form-data">
                @csrf

                <update-profile-form
                    :user="{{ $user->toJson() }}"
                    :skills="{{ $skills->toJson() }}"
                    :assignment-types="{{ $assignmentTypes->toJson() }}"
                    :organizations="{{ $organizations->toJson() }}"
                    :organization-locations="{{ $organizationLocations->toJson() }}"
                    :departments="{{ $departments->toJson() }}"
                    :tags="{{ $tags->toJson() }}"
                    :errors="{{ $errors->toJson() }}"
                    :old-input="{{ $oldInput->toJson() }}"
                    :api-endpoints="{{ $apiEndpoints->toJson() }}"
                    :strings="{{ $strings->toJson() }}"
                    locale="{{ app()->getLocale() }}"
                    back-href="{{ route('profile') }}">
                </update-profile-form>
                
            </form>

        </div>
    </div>
@stop