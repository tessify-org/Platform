@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("poll", $poll) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title & subtitle -->
            <h1 class="page-title centered">@lang("polls.view_title")</h1>
            <h2 class="page-subtitle centered">{{ $poll->title }}</h2>
            
            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Poll -->
            <div id="view-poll" class="elevation-1">

                <!-- Status -->

                <!-- Description -->
                <div id="poll-description">
                    <div id="poll-description__label">@lang("polls.view_description")</div>
                    <div id="poll-description__text">
                        {!! nl2br($poll->description) !!}
                    </div>
                </div>  

                

            </div>

            <!-- Actions -->
            <div class="page-controls">
                <div class="page-controls__right">
                    @can("update", $poll)
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