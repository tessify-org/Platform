@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("group", $group) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback", ["extraMargin" => true])

            <!-- View group -->
            <div id="view-group">
                <div id="view-group__sidebar">

                    @include("partials.groups.view-sidebar", [
                        "group" => $group
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
                                @if (!is_null($group->slogan))
                                    <h2 id="group-subtitle">{{ $group->slogan }}</h2>
                                @endif
                            </div>
                            @if ($group->hidden)
                                <div id="group-header__hidden-wrapper">
                                    <div id="group-header__hidden">
                                        @lang("groups.view_hidden")
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div id="group-content">
                            <!-- Content header -->
                            <div id="group-content__header">
                                <div id="group-content__header-left">

                                    <!-- Group description -->
                                    <div id="group-description">
                                        <div id="group-description__title">@lang("groups.view_description")</div>
                                        <div id="group-description__text">
                                            {!! nl2br($group->description) !!}
                                        </div>
                                    </div>
                            
                                    <!-- Tags -->
                                    @if (count($group->tags))
                                        <div id="group-tags" class="tags">
                                            @foreach ($group->tags as $tag)       
                                                <div class="tag-wrapper">
                                                    <div class="tag">
                                                        {{ $tag->name }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                </div>
                                <div id="group-content__header-right">

                                    <!-- Join & Leave -->
                                    <div id="primary-action">
                                        @if ($group->is_member)
                                            <!-- Leave button -->
                                            <v-btn color="red" href="{{ route('group.leave', $group->slug) }}" depressed block dark>
                                                <i class="fas fa-door-open"></i>
                                                @lang("groups.view_leave")
                                            </v-btn>
                                        @else
                                            @if ($group->has_outstanding_application)
                                                <!-- Disabled join button -->
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on }">
                                                        <div v-on="on">
                                                            <v-btn color="primary" href="{{ route('group.apply', $group->slug) }}" depressed block disabled>
                                                                <i class="far fa-thumbs-up"></i>
                                                                @lang("groups.view_join")
                                                            </v-btn>
                                                        </div>
                                                    </template>
                                                    <span>@lang("groups.join_disabled_outstanding_application")</span>
                                                </v-tooltip>
                                            @else
                                                <!-- Join button -->
                                                <v-btn color="primary" href="{{ route('group.apply', $group->slug) }}" depressed block>
                                                    <i class="far fa-thumbs-up"></i>
                                                    @lang("groups.view_join")
                                                </v-btn>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Follow & Invite -->
                                    <div id="secondary-actions">
                                        <div class="secondary-action">
                                            @if (!Auth::user()->hasSubscribed($group))
                                                <!-- Follow button -->
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn href="{{ route('group.subscribe', ['slug' => $group->slug]) }}" class="icon-only" small depressed v-on="on">
                                                            <i class="far fa-eye"></i>
                                                        </v-btn>
                                                    </template>
                                                    <span>@lang("groups.view_subscribe")</span>
                                                </v-tooltip>
                                            @else
                                                <!-- Unfollow button -->
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn href="{{ route('group.unsubscribe', ['slug' => $group->slug]) }}" class="icon-only" small depressed v-on="on">
                                                            <i class="fas fa-eye-slash"></i>
                                                        </v-btn>
                                                    </template>
                                                    <span>@lang("groups.view_unsubscribe")</span>
                                                </v-tooltip>
                                            @endif
                                        </div>
                                        <div class="secondary-action">
                                            <!-- Invite friend button -->
                                            <group-invite-button
                                                :group="{{ $group->toJson() }}"
                                                :users="{{ $users->toJson() }}"
                                                :strings="{{ $inviteButtonStrings->toJson() }}"
                                                endpoint="{{ route('group.invite', $group->slug) }}">
                                            </group-invite-button>
                                        </div>
                                    </div>

                                    <!-- Share -->
                                    <div id="share-group">
                                        <div id="share-group__text">@lang("groups.view_share_group")</div>
                                        <div id="share-group__socials">
                                            <!-- Facebook -->
                                            <div class="social-wrapper">
                                                <a class="social" href="#">
                                                    <i class="fab fa-facebook-square"></i>
                                                </a>
                                            </div>
                                            <!-- Twitter -->
                                            <div class="social-wrapper">
                                                <a class="social" href="#">
                                                    <i class="fab fa-twitter-square"></i>
                                                </a>
                                            </div>
                                            <!-- LinkedIn -->
                                            <div class="social-wrapper">
                                                <a class="social" href="#">
                                                    <i class="fab fa-linkedin"></i>
                                                </a>
                                            </div>
                                            <!-- WhatsApp -->
                                            <div class="social-wrapper">
                                                <a class="social" href="#">
                                                    <i class="fab fa-whatsapp-square"></i>
                                                </a>
                                            </div>
                                            <!-- E-mail -->
                                            <div class="social-wrapper">
                                                <a class="social" href="#">
                                                    <i class="fas fa-envelope-square"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Page controls -->
                    <div id="group-page-controls" class="page-controls">
                        <div class="page-controls__right">
                            @can("update", $group)
                                <v-btn depressed color="warning" href="{{ route('group.edit', $group->slug) }}" class="elevation-1">
                                    <i class="fas fa-pen-square"></i>
                                    @lang("general.edit")
                                </v-btn>
                            @endcan
                            @can("delete", $group)
                                <v-btn depressed color="red" dark href="{{ route('group.delete', $group->slug) }}" class="elevation-1">
                                    <i class="fas fa-trash"></i>
                                    @lang("general.delete")
                                </v-btn>
                            @endcan
                        </div>
                    </div>
                
                </div>
            </div>

        </div>
    </div>
@stop