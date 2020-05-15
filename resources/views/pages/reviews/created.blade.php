@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("reviews.created", $type, $slug) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title -->
            <h1 class="page-title centered">@lang("reviews.created_title")</h1>

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- Review created -->
            <div id="review-created">
                <!-- Text -->
                <div id="review-created__text">@lang("reviews.created_text")</div>
                <!-- Actions -->
                <div id="review-created__actions">
                    <!-- Back to reviews -->
                    <v-btn href="{{ route('reviews') }}" color="white">
                        <i class="fas fa-arrow-left"></i>
                        Terug naar mijn reviews
                    </v-btn>
                    <!-- View review -->
                    <v-btn href="{{ route('reviews.view', $review->uuid) }}" color="white">
                        <i class="far fa-eye"></i>
                        Bekijk review
                    </v-btn>
                </div>
            </div>

        </div>
    </div>
@stop
