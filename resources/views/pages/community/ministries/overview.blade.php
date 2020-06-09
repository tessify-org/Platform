@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("ministries") !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/city_girl.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" style="margin-left: -5px;">@lang("ministries.title")</h1>
                <h2 id="page-header__subtitle" class="no-margin">@lang("ministries.subtitle")</h2>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")
            
            <!-- Overview -->
            @if ($ministries->count())
                <div class="card-grid thirds">
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