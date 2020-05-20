@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("home") !!}
@stop

@section("content")
    
    <!-- Header -->
    <header id="homepage-header">
        <div id="homepage-header__content">
            @include("partials.feedback")
            <h1 class="page-title">
                @lang("homepage.header_title")
            </h1>
            <h2 class="page-subtitle">
                {!! nl2br(__("homepage.header_subtitle")) !!}
            </h2>
        </div>
        <div id="homepage-header__overlay"></div>
        <div id="homepage-header__bg">
            <animated-header-bg
                :images="{{ $headerImages->toJson() }}">
            </animated-header-bg>
        </div>
    </header>

    <!-- Homepage -->
    <div id="homepage">
        <div class="content-section__wrapper">
            <div class="content-section">

                <!-- CTA Search -->
                <form action="{{ route('search.post') }}" method="post">
                    @csrf
                    <div id="homepage-cta" class="elevation-1">
                        <div id="homepage-cta__title">
                            @lang("homepage.cta_title", ["num_jobs" => $num_tasks])
                        </div>
                        <div id="homepage-cta__subtext">
                            @lang("homepage.cta_subtext")
                        </div>
                        <div id="homepage-cta__search-wrapper">
                            <div id="search-input__wrapper">
                                <input type="text" id="search-input" name="search_query">
                            </div>
                            <div id="search-submit__wrapper">
                                <button type="submit" id="search-submit">
                                    @lang("homepage.cta_search")
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <div id="homepage-cta__link-wrapper">
                            <a href="{{ route('get-started') }}">
                                @lang("homepage.cta_signup")
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Quick links -->
                <div id="homepage-quick-links">
                    <div class="quick-link__wrapper">
                        <a class="quick-link elevation-1" href="#">
                            <span class="quick-link__bg" style="background-image: url({{ asset('storage/images/homepage/do-more.png') }})"></span>
                            <span class="quick-link__bg-overlay"></span>
                            <span class="quick-link__text">
                                @lang("homepage.quick_link_one")
                            </span>
                            <span class="quick-link__arrow">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>
                    </div>
                    <div class="quick-link__wrapper">
                        <a class="quick-link elevation-1" href="#">
                            <span class="quick-link__bg" style="background-image: url({{ asset('storage/images/homepage/inspiration.png') }})"></span>
                            <span class="quick-link__bg-overlay"></span>
                            <span class="quick-link__text">
                                @lang("homepage.quick_link_two")
                            </span>
                            <span class="quick-link__arrow">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>
                    </div>
                    <div class="quick-link__wrapper">
                        <a class="quick-link elevation-1" href="#">
                            <span class="quick-link__bg" style="background-image: url({{ asset('storage/images/homepage/volunteers.png') }})"></span>
                            <span class="quick-link__bg-overlay"></span>
                            <span class="quick-link__text">
                                @lang("homepage.quick_link_three")
                            </span>
                            <span class="quick-link__arrow">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Second CTA -->
                <div id="homepage-second-cta">
                    <div id="second-cta" class="elevation-1">
                        <div id="second-cta__left">
                            <div id="second-cta__title">
                                @lang("homepage.second_cta_title", ["num_jobs" => $num_projects])
                            </div>
                            <div id="second-cta__subtext">
                                @lang("homepage.second_cta_subtitle")
                            </div>
                            <a id="second-cta__button" href="#">
                                @lang("homepage.second_cta_signup")
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div id="second-cta__right">
                            <div id="second-cta__image" style="background-image: url({{ asset('storage/images/homepage/remote.png') }})"></div>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ -->
                <div id="homepage-faq">
                    <h3 id="homepage-faq__title">
                        @lang("homepage.faq_title")
                    </h3>
                    <div id="homepage-faq__list">
                        <a href="#" class="faq">
                            <span class="faq-icon">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="faq-text">
                                @lang("homepage.faq_one")
                            </span>
                        </a>
                        <a href="#" class="faq">
                            <span class="faq-icon">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="faq-text">
                                @lang("homepage.faq_two")
                            </span>
                        </a>
                        <a href="#" class="faq">
                            <span class="faq-icon">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="faq-text">
                                @lang("homepage.faq_three")
                            </span>
                        </a>
                    </div>
                    <div id="homepage-faq__cta-wrapper">
                        <a href="#" id="homepage-faq__cta">
                            @lang("homepage.faq_view_all")
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- How it works
                <div class="content-section__wrapper">
                    <div class="content-section">
                        <h3 class="content-section__title centered">Hoe het werkt</h3>
                        <div id="how-it-works">
                            <div class="how-it-works__step-wrapper">
                                <div class="how-it-works__step">
                                    <div class="how-it-works__step-image"></div>
                                    <div class="how-it-works__text">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam hendrerit ante vitae massa gravida, at interdum odio auctor. Fusce sodales pharetra urna eu vestibulum. Sed condimentum quam non gravida consequat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. 
                                    </div>
                                </div>
                            </div>
                            <div class="how-it-works__step-wrapper">
                                <div class="how-it-works__step">
                                    <div class="how-it-works__step-image"></div>
                                    <div class="how-it-works__text">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam hendrerit ante vitae massa gravida, at interdum odio auctor. Fusce sodales pharetra urna eu vestibulum. Sed condimentum quam non gravida consequat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                                    </div>
                                </div>
                            </div>
                            <div class="how-it-works__step-wrapper">
                                <div class="how-it-works__step">
                                    <div class="how-it-works__step-image"></div>
                                    <div class="how-it-works__text">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam hendrerit ante vitae massa gravida, at interdum odio auctor. Fusce sodales pharetra urna eu vestibulum. Sed condimentum quam non gravida consequat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- What it means for you 
                <div class="content-section__wrapper alt">
                    <div class="content-section">
                        <h3 class="content-section__title centered">Wat betekend dit voor jou?</h3>
                        <div id="what-it-means">
                            <div id="what-it-means__text">
                                Stuff and things. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam hendrerit ante vitae massa gravida, 
                                at interdum odio auctor. Fusce sodales pharetra urna eu vestibulum. Sed condimentum quam non gravida consequat. 
                                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Pellentesque nec 
                                leo elementum, venenatis mauris ac, viverra mi. In id quam mattis, vestibulum enim vitae, ornare augue. Nam volutpat 
                                quam ut lectus euismod, eget gravida dui rutrum. Nam blandit dignissim sem at efficitur. Praesent molestie massa felis, 
                                a luctus lectus ultricies at. Proin a risus efficitur diam lacinia cursus ac in velit. Aliquam lacus mauris, fringilla 
                                eleifend fringilla ut, sagittis quis nisl. Praesent rhoncus pulvinar placerat. 
                            </div>
                            <div id="what-it-means__image-wrapper">
                                <div id="what-it-means__image"></div>
                            </div>
                        </div>
                    </div>
                </div>-->

                <!-- Success verhalen
                <div class="content-section__wrapper">
                    <div class="content-section">
                        <h3 class="content-section__title centered">Succesverhalen</h3>
                        <div id="success-stories">
                            <div class="success-story-wrapper">
                                <div class="success-story">
                                    <div class="success-story__title">Gemeente Utrecht</div>
                                    <div class="success-story__text">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam hendrerit ante vitae massa gravida, 
                                        at interdum odio auctor. Fusce sodales pharetra urna eu vestibulum. Sed condimentum quam non gravida 
                                        consequat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. 
                                        Pellentesque nec leo elementum, venenatis mauris ac, viverra mi.
                                    </div>
                                </div>
                            </div>
                            <div class="success-story-wrapper">
                                <div class="success-story">
                                    <div class="success-story__title">MinBZK</div>
                                    <div class="success-story__text">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam hendrerit ante vitae massa gravida, 
                                        at interdum odio auctor. Fusce sodales pharetra urna eu vestibulum. Sed condimentum quam non gravida 
                                        consequat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. 
                                        Pellentesque nec leo elementum, venenatis mauris ac, viverra mi.
                                    </div>
                                </div>
                            </div>
                            <div class="success-story-wrapper">
                                <div class="success-story">
                                    <div class="success-story__title">Mijn oma</div>
                                    <div class="success-story__text">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam hendrerit ante vitae massa gravida, 
                                        at interdum odio auctor. Fusce sodales pharetra urna eu vestibulum. Sed condimentum quam non gravida 
                                        consequat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. 
                                        Pellentesque nec leo elementum, venenatis mauris ac, viverra mi.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div> -->

            </div>
        </div>
    </div>

@stop