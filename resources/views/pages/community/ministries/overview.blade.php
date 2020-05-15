@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("ministries") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Title & subtitle -->
            <h1 class="page-title centered">@lang("ministries.title")</h1>
            <h2 class="page-subtitle centered">@lang("ministries.subtitle")</h2>

            <!-- Feedback -->
            @include("partials.feedback")
            
            <!-- Overview -->
            @if ($ministries->count())
                <div class="card-grid">
                    @foreach ($ministries as $ministry)
                        <div class="card-wrapper">
                            <a class="card elevation-1" href="{{ route('ministries.view', $ministry->slug) }}">
                                <span class="card-title">{{ $ministry->name }}</span>
                                <span class="card-subtitle">{{ $ministry->abbreviation }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-records elevation-1">
                    @lang("ministries.no_records")
                </div>
            @endif
            
        </div>
    </div>
@stop