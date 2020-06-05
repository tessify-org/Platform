@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("group.forum.update-thread", $group, $forum, $thread) !!}
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
                                <h1 id="group-title">@lang("forums.threads_update_title")</h1>
                                <h2 id="group-subtitle">{{ $thread->title }}</h2>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div id="group-content">

                            <form action="{{ route('group.forum.update-thread.post', ['slug' => $group->slug, 'forumSlug' => $forum->slug, 'threadSlug' => $thread->slug]) }}" method="post">
                                @csrf
                                <forum-thread-form
                                    :group="{{ $group->toJson() }}"
                                    :thread="{{ $thread->toJson() }}"
                                    :strings="{{ $strings->toJson() }}"
                                    :old-input="{{ $oldInput->toJson() }}"
                                    :errors="{{ $errors->toJson() }}"
                                    back-href="{{ route('group.forum.thread', ['slug' => $group->slug, 'forumSlug' => $thread->forum->slug, 'threadSlug' => $thread->slug]) }}">
                                </forum-thread-form>
                            </form>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
@stop