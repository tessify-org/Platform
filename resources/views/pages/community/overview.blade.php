@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("community") !!}
@stop

@section("content")

    <!-- Header -->
    <div id="page-header" class="narrow">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-overlay"></div>
        <div id="page-header__content">

            <!-- Title -->
            <h1 id="page-header__title" class="no-margin">@lang("community.title")</h1>
            
        </div>
    </div>

    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Content -->
            <div id="community-links" class="card-grid">
                <!-- Ministries -->
                <div class="card-wrapper">
                    <a class="card elevation-1" href="{{ route('ministries') }}">
                        <span class="card-icon">
                            <i class="fas fa-chess-rook"></i>
                        </span>
                        <span class="card-title">
                            @lang("community.ministries")
                        </span>
                    </a>
                </div>
                <!-- Organizations -->
                <div class="card-wrapper">
                    <a class="card elevation-1" href="{{ route('organizations') }}">
                        <span class="card-icon">
                            <i class="fas fa-building"></i>
                        </span>
                        <span class="card-title">
                            @lang("community.organizations")
                        </span>
                    </a>
                </div>
                <!-- Forum -->
                <div class="card-wrapper">
                    <a class="card elevation-1" href="{{ route('groups') }}">
                        <span class="card-icon">
                            <i class="fas fa-comments"></i>
                        </span>
                        <span class="card-title">
                            @lang("community.forum")
                        </span>
                    </a>
                </div>
                <!-- Polls -->
                <div class="card-wrapper">
                    <a class="card elevation-1" href="{{ route('groups') }}">
                        <span class="card-icon">
                            <i class="fas fa-poll"></i>
                        </span>
                        <span class="card-title">
                            @lang("community.polls")
                        </span>
                    </a>
                </div>
                <!-- Groups -->
                <div class="card-wrapper">
                    <a class="card elevation-1" href="{{ route('groups') }}">
                        <span class="card-icon">
                            <i class="fas fa-users"></i>
                        </span>
                        <span class="card-title">
                            @lang("community.groups")
                        </span>
                    </a>
                </div>
                <!-- Memberlist -->
                <div class="card-wrapper">
                    <a class="card elevation-1" href="{{ route('memberlist') }}">
                        <span class="card-icon">
                            <i class="fas fa-users"></i>
                        </span>
                        <span class="card-title">
                            @lang("community.memberlist")
                        </span>
                    </a>
                </div>
            </div>

        </div>
    </div>
@stop