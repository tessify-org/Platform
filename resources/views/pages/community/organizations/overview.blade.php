@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("organizations") !!}
@stop

@section("content")
    
    <!-- Page header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/best_place.svg') }}); width: 400px;"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" class="no-margin" style="margin-left: -5px;">@lang("organizations.title")</h1>
            </div>
        </div>
    </div>
    
    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">
            
            <!-- Feedback -->
            @include("partials.feedback")
            
            <!-- Overview -->
            @if ($organizations->count())
                <div class="card-grid">
                    @foreach ($organizations as $organization)
                        <div class="card-wrapper">
                            <a class="card elevation-1" href="{{ route('organizations.view', $organization->slug) }}">
                                <span class="card-title">{{ $organization->name }}</span>
                                <span class="card-subtitle">{{ $organization->abbreviation }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-records elevation-1">
                    @lang("organizations.no_records")
                </div>
            @endif
            
        </div>
    </div>
    
@stop