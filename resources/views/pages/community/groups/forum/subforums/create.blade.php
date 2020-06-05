@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("group.forum.create-subforum", $group, $forum) !!}
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
                                <h1 id="group-title">@lang("forums.subforums_create_title")</h1>
                                <h2 id="group-subtitle">{{ $forum->title }}</h2>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div id="group-content">

                            <form action="{{ route('group.forum.create-subforum.post', ['slug' => $group->slug, 'forumSlug' => $forum->slug]) }}" method="post">
                                @csrf
                                <forum-subforum-form
                                    :group="{{ $group->toJson() }}"
                                    :strings="{{ $strings->toJson() }}"
                                    :old-input="{{ $oldInput->toJson() }}"
                                    :errors="{{ $errors->toJson() }}"
                                    :errors="{{ $errors->toJson() }}"
                                    back-href="{{ route('group.forum', ['slug' => $group->slug, 'forumSlug' => $forum->slug]) }}">
                                </forum-subforum-form>
                            </form>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
@stop