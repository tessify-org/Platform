@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("profile", $user) !!}
@stop

@section("content")
    <div id="profile">

        <!-- Header -->
        <div id="profile-header" style="background-image: url({{ asset($user->header_bg_url) }})">
            <!-- Content -->
            <div id="profile-header__content">
                
                <!-- Actions -->
                <div id="profile-header__actions">
                    <!-- Follow / Unfollow -->
                    @if (Auth::user()->id != $user->id)
                        @if (Auth::user()->isFollowing($user))
                            <v-btn depressed href="{{ route('profile.unfollow', $user->slug) }}">
                                <i class="fas fa-eye-slash"></i>
                                @lang("followers.unfollow")
                            </v-btn>
                        @else
                            <v-btn depressed href="{{ route('profile.follow', $user->slug) }}">
                                <i class="fas fa-eye"></i>
                                @lang("followers.follow")
                            </v-btn>
                        @endif
                    @endif
                    <!-- Update profile -->
                    @if (Auth::user()->id == $user->id)
                        <v-btn depressed color="primary" href="{{ route('profile.update') }}">
                            <i class="fas fa-user-edit"></i>
                            @lang("profiles.profile_update_button")
                        </v-btn>
                    @endif
                </div>
                
                <!-- Avatar -->
                <div id="profile-header__avatar" style="background-image: url({{ asset($user->avatar_url) }})"></div>
                
                <!-- Title -->
                <h1 id="profile-header__title">{{ $user->formattedName }}</h1>
                
                <!-- Job title -->
                @if ($user->currentAssignment)
                    <h2 id="profile-header__subtitle">
                        {{ $user->currentAssignmentJobTitle }}
                    </h2>
                @endif
                
                <!-- Headline -->
                @if ($user->headline != "")
                    <h3 id="profile-header__headline">"{{ $user->headline }}"</h3>
                @endif
                
                <!-- Following -->
                <div id="profile-header__following">
                
                    <!-- Followers -->
                    <div class="following-entry">
                        <followers-bar 
                            :followers="{{ $followers->toJson() }}"
                            label-text="@lang('followers.followers_label')"
                            no-followers-text="@lang('followers.followers_empty')"
                            dialog-title-text="@lang('followers.followers_dialog_title')">
                        </followers-bar>
                    </div>
                    
                    <!-- Following -->
                    <div class="following-entry">
                        <followers-bar 
                            :followers="{{ $following->toJson() }}"
                            label-text="@lang('followers.following_label')"
                            no-followers-text="@lang('followers.following_empty')"
                            dialog-title-text="@lang('followers.following_dialog_title')">
                        </following-bar>
                    </div>
                
                </div>

            </div>    
            <!-- Overlay -->
            <div id="profile-header__overlay"></div>
        </div>
        
        <!-- Content wrapper -->
        <div class="content-section__wrapper">
            <div class="content-section pt50">

                <!-- Profile -->
                <div id="profile-content__wrapper">

                    <!-- Feedback -->
                    @include("partials.feedback")

                    <!-- Profile content -->
                    <div id="profile-content">
                        <div id="profile-content__left">

                            <!-- Personal information -->
                            <h3 class="content-card__title">@lang("profiles.profile_personal_info")</h3>
                            <div class="content-card mb elevation-1">
                                <div class="details mb-0">
                                    <!-- Name -->
                                    <div class="detail">
                                        <div class="key">@lang("profiles.profile_name")</div>
                                        <div class="val">{{ $user->formattedName }}</div>
                                    </div>
                                    <!-- Email -->
                                    <div class="detail">
                                        <div class="key">@lang("profiles.profile_email")</div>
                                        <div class="val">
                                            @if ($is_mine || $can_view_email)
                                                {{ $user->email }}
                                            @else
                                                <div id="email-field">
                                                    <div id="email-field__left">
                                                        <span class="italic">
                                                            @lang("general.hidden")
                                                        </span>
                                                    </div>
                                                    <div id="email-field__right">
                                                        <request-email-access-button
                                                            :has-sent-request="{{ json_encode($has_sent_view_email_request) }}"
                                                            button-href="{{ route('profile.request-access-email', $user->slug) }}"
                                                            button-text="@lang('profiles.profile_email_request_button')"
                                                            sent-text="@lang('profiles.profile_email_request_sent')">
                                                        </request-email-access-button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Email activated -->
                                    @if (auth()->user()->is_admin)
                                        <div class="detail">
                                            <div class="key">@lang("profiles.profile_email_activated")</div>
                                            <div class="val">
                                                @if (is_null($user->email_activated_at))
                                                    <span class="red-text">
                                                        @lang("profiles.profile_email_not_activated")
                                                    </span>
                                                @else
                                                    <span class="green-text">
                                                        @lang("profiles.profile_email_activated_on", ["date" => $user->email_activated_at->format("d-m-Y")])
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    <!-- Phone number -->
                                    <div class="detail">
                                        <div class="key">@lang("profiles.profile_phone_nr")</div>
                                        <div class="val">
                                            @if (is_null($user->phone))
                                                <span class="italic">
                                                    @lang("profiles.missing")
                                                </span>
                                            @else
                                                {{ $user->phone }}
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Created at -->
                                    <div class="detail">
                                        <div class="key">@lang("profiles.profile_created_at")</div>
                                        <div class="val">{{ $user->created_at->format("d-m-Y") }}</div>
                                    </div>
                                    <!-- Updated at -->
                                    <div class="detail">
                                        <div class="key">@lang("profiles.profile_updated_at")</div>
                                        <div class="val">{{ $user->updated_at->format("d-m-Y") }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- About me & interests -->
                            @if ($user->interests !== null and $user->interests !== "")
                                <h3 class="content-card__title">@lang("profiles.profile_about")</h3>
                                <div class="content-card mb elevation-1">
                                    <div class="content-card__content">
                                        <div id="about-me">
                                            <div id="about-me__text">
                                                @if (!is_null($user->about_me))
                                                    {!! nl2br($user->about_me) !!}
                                                @else
                                                    <span class="italic">@lang("profiles.profile_about_missing")</span>
                                                @endif
                                            </div>
                                            <div id="about-me__interests">
                                                <div id="about-me__interests-label">@lang("profiles.profile_interests")</div>
                                                @if ($user->interests->count())
                                                    <div id="about-me__interests-list">
                                                        @foreach ($user->interests as $tag)
                                                            <div class="interest-wrapper">
                                                                <div class="interest">
                                                                    {{ $tag->name }}
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div id="about-me__interests-missing">
                                                        @lang("profiles.profile_interests_missing")
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Assignments -->
                            <h3 class="content-card__title">@lang("profiles.profile_assignments")</h3>
                            <div class="content-card mb elevation-1">
                                <profile-assignment-list 
                                    :assignments="{{ json_encode($assignments) }}"
                                    :strings="{{ $assignmentListStrings->toJson() }}"
                                    locale="{{ app()->getLocale() }}">
                                </profile-assignment-list>
                            </div>

                            <!-- Reviews -->
                            <h3 class="content-card__title">@lang("profiles.profile_reviews")</h3>
                            <div class="content-card mb elevation-1">
                                <div class="content-card__content">
                                    Coming soon..
                                </div>
                            </div>

                            <!-- Reacties -->
                            <!-- <h3 class="content-card__title">
                                @lang("profiles.profile_reactions")
                            </h3>
                            <div class="content-card mb elevation-1">
                                <div class="content-card__content">
                                    Coming soon..
                                </div>
                            </div> -->
                            
                            <!-- Comments -->
                            <div class="content-box elevation-1">
                                <comments
                                    :user="{{ $user->toJson() }}"
                                    :comments="{{ $comments->toJson() }}"
                                    per-page="3"
                                    target-type="user"
                                    target-id="{{ $user->id }}"
                                    create-comment-api-endpoint="{{ route('api.comments.create.post') }}"
                                    update-comment-api-endpoint="{{ route('api.comments.update.post') }}"
                                    delete-comment-api-endpoint="{{ route('api.comments.delete.post') }}"
                                    title-text="@lang('comments.title')"
                                    no-comments-text="@lang('comments.no_comments')"
                                    create-title-text="@lang('comments.create_title')"
                                    create-submit-text="@lang('comments.create_submit')"
                                    update-title-text="@lang('comments.update_title')"
                                    update-cancel-text="@lang('comments.update_cancel')"
                                    update-submit-text="@lang('comments.update_submit')"
                                    delete-title-text="@lang('comments.delete_title')"
                                    delete-text="@lang('comments.delete_text')"
                                    delete-cancel-text="@lang('comments.delete_cancel')"
                                    delete-submit-text="@lang('comments.delete_submit')">
                                </comments>
                            </div>

                        </div>
                        <div id="profile-content__right">

                            <!-- Skills -->
                            <h3 class="content-card__title">
                                @lang("profiles.profile_skills")
                            </h3>
                            <div class="content-card mb elevation-1">
                                <profile-skill-list 
                                    :skills="{{ $user->skills->toJson() }}"
                                    no-skills-text="@lang('profiles.profile_no_skills')"
                                    locale="{{ app()->getLocale() }}">
                                </profile-skill-list>
                            </div>

                            <!-- Reputation -->
                            <h3 class="content-card__title">@lang("profiles.profile_reputation_history_title")</h3>
                            <div class="content-card mb elevation-1">
                                <div class="content-card__content">
                                    <div>
                                        {{ number_format($user->reputation_points, 0, ".", ",") }} reputatie punten
                                    </div>
                                    <reputation-history-button 
                                        :transactions="{{ $transactions->toJson() }}"
                                        button-text="@lang('profiles.profile_reputation_history_button')"
                                        dialog-title-text="@lang('profiles.profile_reputation_history_dialog_title')"
                                        no-transactions-text="@lang('profiles.profile_reputation_history_dialog_no_transactions')">
                                    </reputation-history-button>
                                </div>
                            </div>

                            <!-- Current projects -->
                            <h3 class="content-card__title">
                                @lang("profiles.profile_current_projects")
                            </h3>
                            <div class="content-card mb elevation-1">
                                <profile-project-list 
                                    :projects="{{ $projects->toJson() }}"
                                    locale="{{ app()->getLocale() }}"
                                    no-records-text="@lang('profiles.profile_projects_no_projects')">
                                </profile-project-list>
                            </div>

                            <!-- Current tasks -->
                            <h3 class="content-card__title">
                                @lang("profiles.profile_current_tasks")
                            </h3>
                            <div class="content-card elevation-1">
                                <profile-task-list 
                                    :tasks="{{ $tasks->toJson() }}"
                                    locale="{{ app()->getLocale() }}"
                                    no-records-text="@lang('profiles.profile_tasks_no_tasks')">
                                </profile-task-list>
                            </div>

                        </div>
                    </div>

                </div>  

            </div>
        </div>
    </div>
@stop