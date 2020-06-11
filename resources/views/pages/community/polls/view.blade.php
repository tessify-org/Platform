@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("poll", $poll) !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg" style="background-image: url({{ asset($poll->header_image_url) }})"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">
            <h1 id="page-header__title" class="no-margin">{{ $poll->title }}</h1>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">
            
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Turn content into tabs magically -->
            @if ($poll->has_voted || $poll->is_owner)
                <v-tabs class="elevation-1">
                    <v-tab>@lang("polls.view_information")</v-tab>
                    <v-tab>@lang("polls.view_results")</v-tab>
                    <v-tab-item>
            @endif

                <!-- Poll information -->
                <div id="view-poll" @if (!$poll->has_voted && !$poll->is_owner) class="elevation-1" @endif>
                    <div id="view-poll__left">

                        <!-- Shared with --->
                        <div id="poll-shared-with">
                            <div id="poll-shared-with__label">@lang("polls.view_shared_with")</div>
                            <div id="poll-shared-with__text">
                                @if ($poll->parent)
                                    @if ($poll->pollable_type == "App\Models\Group")
                                        <a href="{{ $poll->parent->view_href }}">{{ $poll->parent->name }}</a>
                                    @endif
                                @else
                                    @lang("polls.view_shared_with_everyone")
                                @endif
                            </div>
                        </div>

                        <!-- Description -->
                        <div id="poll-description">
                            <div id="poll-description__label">@lang("polls.view_description")</div>
                            <div id="poll-description__text">
                                {!! nl2br($poll->description) !!}
                            </div>
                        </div>

                        <!-- Draft version -->
                        @if (!$poll->published)
                            <div id="poll-draft__wrapper">
                                <div id="poll-draft">
                                    @lang("polls.view_draft_version")
                                </div>
                            </div>
                        @elseif ($poll->status->name === "Open")
                            <div id="poll-status__wrapper">
                                <div id="poll-status" class="{{ strtolower($poll->status->name) }}">
                                    {{ $poll->status->name }}
                                </div>
                            </div>
                        @endif
                
                    </div>
                    @if ($poll->has_voted)
                        <div id="view-poll__right">
                            <div id="thanks-for-voting">
                                <div id="thanks-for-voting__text">@lang("polls.view_thanks_for_voting")</div>
                                <div id="thanks-for-voting__image-wrapper">
                                    <img id="thanks-for-voting__image" src="{{ asset('storage/images/undraw/super_thank_you.svg') }}">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            <!-- End of magically turning it into tabs -->
            @if ($poll->has_voted || $poll->is_owner)
                    </v-tab-item>
                    <v-tab-item>

                        <!-- Poll results -->
                        <div id="poll-results__wrapper">
                            
                            <div id="poll-num-votes">
                                <div id="poll-num-votes__label">@lang("polls.view_num_votes")</div>
                                <div id="poll-num-votes__text">
                                    {{ $poll->num_votes }}
                                </div>
                            </div>

                            <poll-results
                                :results="{{ json_encode($poll->results) }}"
                                :strings="{{ $resultStrings->toJson() }}"
                                locale="{{ app()->getLocale() }}">
                            </poll-results>

                        </div>

                    </v-tab-item>
                </v-tabs>

            @endif

            <!-- Questions form -->
            @if (!$poll->has_voted)
                <div id="view-poll__questions">
                    <form action="{{ route('poll.vote.post', $poll->slug) }}" method="post">
                        @csrf
                        <poll-questions-form
                            :poll="{{ $poll->toJson() }}"
                            :strings="{{ $strings->toJson() }}"
                            :old-input="{{ $oldInput->toJson() }}"
                            :errors="{{ $errors->toJson() }}"
                            locale="{{ app()->getLocale() }}"
                            back-href="{{ route('polls') }}">
                        </poll-questions-form>
                    </form>
                </div>
            @endif

            <!-- Actions -->
            <div id="view-poll__page-controls" class="page-controls">
                <div class="page-controls__left">
                    <user-pill :user="{{ $poll->user->toJson() }}" with-transparent transparent></user-pill>
                </div>
                <div class="page-controls__right">
                    @can("update", $poll)
                        @if ($poll->status->name == "Closed" || $poll->status->name == "Gesloten")
                            <v-btn href="{{ route('poll.reopen', $poll->slug) }}" color="primary">
                                <i class="fas fa-lock-open"></i>
                                @lang("polls.view_reopen")
                            </v-btn>
                        @else
                            <v-btn href="{{ route('poll.close', $poll->slug) }}" color="red" dark>
                                <i class="fas fa-lock"></i>
                                @lang("polls.view_close")
                            </v-btn>
                        @endif
                        <v-btn color="warning" href="{{ route('poll.edit', $poll->slug) }}">
                            <i class="fas fa-pen-square"></i>
                            @lang("general.edit")
                        </v-btn>
                    @endcan
                    @can("delete", $poll)
                        <v-btn color="red" dark href="{{ route('poll.delete', $poll->slug) }}">
                            <i class="fas fa-trash"></i>
                            @lang("general.delete")
                        </v-btn>
                    @endcan
                </div>  
            </div>  
            
        </div>
    </div>
@stop