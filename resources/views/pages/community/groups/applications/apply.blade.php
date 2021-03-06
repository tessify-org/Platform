@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("group.members", $group) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- View group -->
            <div id="view-group">
                <div id="view-group__sidebar">

                    @include("partials.groups.view-sidebar", [
                        "group" => $group,
                    ])

                </div>
                <div id="view-group__content">

                    <!-- Group -->
                    <div id="group" class="elevation-2">

                        <!-- Group header -->
                        <div id="group-header">
                            <div id="group-header__bg" style="background-image: url({{ asset($group->header_image_url) }})"></div>
                            <div id="group-header__bg-overlay"></div>
                            <div id="group-header__text">
                                <h1 id="group-title">{{ $group->name }}</h1>
                                <h2 id="group-subtitle">@lang("groups.apply_title")</h2>
                            </div>
                        </div>

                        <!-- Content -->
                        <div id="group-content">

                            <form action="{{ route('group.apply.post', $group->slug) }}" method="post">
                                @csrf

                                <group-application-form
                                    :old-input="{{ $oldInput->toJson() }}"
                                    :errors="{{ $errors->toJson() }}"
                                    :strings="{{ $strings->toJson() }}">
                                </group-application-form>

                                <div class="form-controls">
                                    <div class="form-controls__left">
                                        <v-btn text href="{{ route('group', $group->slug) }}">
                                            <i class="fas fa-arrow-left"></i>
                                            @lang("groups.apply_cancel")
                                        </v-btn>
                                    </div>
                                    <div class="form-controls__right">
                                        <v-btn dark depressed type="submit" color="success">
                                            <i class="fas fa-save"></i>
                                            @lang("groups.apply_submit")
                                        </v-btn>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
@stop