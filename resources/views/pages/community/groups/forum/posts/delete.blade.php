@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("group.forum.delete-post", $group, $forum, $thread, $post) !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <!-- Feedback -->
            @include("partials.feedback")

            <!-- View group -->
            <div id="view-group">
                <div id="view-group__sidebar">
                    
                    <!-- Sidebar -->
                    @include("partials.groups.view-sidebar", [
                        "group" => $group,
                        "page" => "forum",
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
                                <h1 id="group-title">@lang("groups.forum_delete_post_title")</h1>
                                <h2 id="group-subtitle">{{ $thread->title }}</h2>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div id="group-content">

                            <form action="{{ route('group.forum.delete-post.post', ['slug' => $group->slug, 'forumSlug' => $forum->slug, 'threadSlug' => $thread->slug, 'uuid' => $post->uuid]) }}" method="post">
                                @csrf
                                <p>@lang("groups.forum_delete_post_text")</p>
                                <div class="form-controls">
                                    <div class="form-controls__left">
                                        <v-btn depressed href="{{ route('group.forum.thread', ['slug' => $group->slug, 'forumSlug' => $forum->slug, 'threadSlug' => $thread->slug]) }}">
                                            <i class="fas fa-arrow-left"></i>
                                            @lang("groups.forum_delete_post_cancel")
                                        </v-btn>
                                    </div>
                                    <div class="form-controls__right">
                                        <v-btn depressed dark color="red" type="submit">
                                            <i class="fas fa-trash-alt"></i>
                                            @lang("groups.forum_delete_post_submit")
                                        </v-btn>
                                    </div>
                                </div>
                            </form>
                            
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
@stop