@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("community") !!}
@stop

@section("content")

    <!-- Page header -->
    <div id="page-header" class="narrow light">
        <div id="page-header__bg"></div>
        <div id="page-header__bg-illustration">
            <div id="bg-illustration__wrapper">
                <div id="bg-illustration" style="background-image: url({{ asset('storage/images/undraw/having_fun.svg') }})"></div>
            </div>
        </div>
        <div id="page-header__content" class="align-left">
            <div id="page-header__content-wrapper">
                <h1 id="page-header__title" style="margin-left: -5px;">@lang("community.title")</h1>
                <h2 id="page-header__subtitle" class="no-margin">@lang("community.subtitle")</h2>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Community links -->
            <div id="community-links">

                <!-- Ministries -->
                <div class="community-link__wrapper">
                    <a class="community-link elevation-1" href="{{ route('ministries') }}">
                        <span class="community-link__image-wrapper">
                            <span class="community-link__image" style="background-image: url({{ asset('storage/images/unsplash/hague.jpg') }})"></span>
                            <span class="community-link__image-overlay"></span>
                        </span>
                        <span class="community-link__text-wrapper">
                            <span class="community-link__title">
                                <span class="community-link__title-icon">
                                    <i class="fas fa-chess-rook"></i>
                                </span>
                                <span class="community-link__title-text">
                                    @lang("community.ministries")
                                </span>
                            </span>
                            <span class="community-link__text">
                                @lang("community.ministries_summary")
                            </span>
                        </span>
                    </a>
                </div>
                
                <!-- Organizations -->
                <div class="community-link__wrapper">
                    <a class="community-link elevation-1" href="{{ route('organizations') }}">
                        <span class="community-link__image-wrapper">
                            <span class="community-link__image" style="background-image: url({{ asset('storage/images/unsplash/suit.jpg') }})"></span>
                            <span class="community-link__image-overlay"></span>
                        </span>
                        <span class="community-link__text-wrapper">
                            <span class="community-link__title">
                                <span class="community-link__title-icon">
                                    <i class="fas fa-city"></i>
                                </span>
                                <span class="community-link__title-text">
                                    @lang("community.organizations")
                                </span>
                            </span>
                            <span class="community-link__text">
                                @lang("community.organizations_summary")
                            </span>
                        </span>
                    </a>
                </div>

                <!-- Forum -->
                <div class="community-link__wrapper">
                    <a class="community-link elevation-1" href="{{ route('forum') }}">
                        <span class="community-link__image-wrapper">
                            <span class="community-link__image" style="background-image: url({{ asset('storage/images/unsplash/forum.jpg') }})"></span>
                            <span class="community-link__image-overlay"></span>
                        </span>
                        <span class="community-link__text-wrapper">
                            <span class="community-link__title">
                                <span class="community-link__title-icon">
                                    <i class="fas fa-comments"></i>
                                </span>
                                <span class="community-link__title-text">
                                    @lang("community.forum")
                                </span>
                            </span>
                            <span class="community-link__text">
                                @lang("community.forum_summary")
                            </span>
                        </span>
                    </a>
                </div>
                
                <!-- Groups -->
                <div class="community-link__wrapper">
                    <a class="community-link elevation-1" href="{{ route('groups') }}">
                        <span class="community-link__image-wrapper">
                            <span class="community-link__image" style="background-image: url({{ asset('storage/images/unsplash/groups.jpg') }})"></span>
                            <span class="community-link__image-overlay"></span>
                        </span>
                        <span class="community-link__text-wrapper">
                            <span class="community-link__title">
                                <span class="community-link__title-icon">
                                    <i class="fas fa-users"></i>
                                </span>
                                <span class="community-link__title-text">
                                    @lang("community.groups")
                                </span>
                            </span>
                            <span class="community-link__text">
                                @lang("community.groups_summary")
                            </span>
                        </span>
                    </a>
                </div>

                <!-- Polls -->
                <div class="community-link__wrapper">
                    <a class="community-link elevation-1" href="{{ route('polls') }}">
                        <span class="community-link__image-wrapper">
                            <span class="community-link__image" style="background-image: url({{ asset('storage/images/unsplash/polls.jpg') }})"></span>
                            <span class="community-link__image-overlay"></span>
                        </span>
                        <span class="community-link__text-wrapper">
                            <span class="community-link__title">
                                <span class="community-link__title-icon">
                                    <i class="fas fa-poll"></i>
                                </span>
                                <span class="community-link__title-text">
                                    @lang("community.polls")
                                </span>
                            </span>
                            <span class="community-link__text">
                                @lang("community.polls_summary")
                            </span>
                        </span>
                    </a>
                </div>
                
                <!-- Memberlist -->
                <div class="community-link__wrapper">
                    <a class="community-link elevation-1" href="{{ route('memberlist') }}">
                        <span class="community-link__image-wrapper">
                            <span class="community-link__image" style="background-image: url({{ asset('storage/images/unsplash/suit.jpg') }})"></span>
                            <span class="community-link__image-overlay"></span>
                        </span>
                        <span class="community-link__text-wrapper">
                            <span class="community-link__title">
                                <span class="community-link__title-icon">
                                    <i class="fas fa-users"></i>
                                </span>
                                <span class="community-link__title-text">
                                    @lang("community.memberlist")
                                </span>
                            </span>
                            <span class="community-link__text">
                                @lang("community.memberlist_summary")
                            </span>
                        </span>
                    </a>
                </div>

            </div>

        </div>
    </div>
@stop