<!-- Ownership -->
<div id="group-ownership">
    <div id="group-ownership__title">@lang("groups.view_founder")</div>
    <div id="group-ownership__content" class="elevation-1">

        <!-- Founder -->
        <div id="ownership-founder">
            <a href="{{ route('profile', $group->founder->slug) }}" id="founder">
                <span id="founder-avatar" style="background-image: url({{ asset($group->founder->avatar_url) }})"></span>
                <span id="founder-name">{{ $group->founder->formatted_name }}</span>
            </a>
        </div>

    </div>
</div>

<!-- Links -->
<div id="group-links">
    <div id="group-links__title">@lang("groups.view_links")</div>
    <div id="group-links__links" class="elevation-1">
        <!-- Group members -->
        <a class="group-link @if (isset($page) && $page == 'team') selected @endif" href="{{ route('group.members', $group->slug) }}">
            <span class="group-link__icon">
                <i class="fas fa-users"></i>
            </span>
            <span class="group-link__text">
                @lang("groups.view_link_members") ({{ $group->num_members }})
            </span>
        </a>
        <!-- Group roles -->
        <a class="group-link @if (isset($page) && $page == 'roles') selected @endif" href="{{ route('group.roles', $group->slug) }}">
            <span class="group-link__icon">
                <i class="fas fa-user-tag"></i>
            </span>
            <span class="group-link__text">
                @lang("groups.view_link_roles") <group-role-counter count="{{ $group->num_roles }}"></group-role-counter>
            </span>
        </a>
        <!-- Group applications -->
        <a class="group-link @if (isset($page) && $page == 'applications') selected @endif" href="{{ route('group.applications', $group->slug) }}">
            <span class="group-link__icon">
                <i class="fas fa-user-plus"></i>
            </span>
            <span class="group-link__text">
                @lang("groups.view_link_applications") <group-application-counter count="{{ $group->num_outstanding_applications }}"></group-application-counter>
            </span>
        </a>
        <!-- Polls -->
        <a class="group-link @if (isset($page) && $page == 'polls') selected @endif" href="{{ route('group.polls', $group->slug) }}">
            <span class="group-link__icon">
                <i class="fas fa-poll"></i>
            </span>
            <span class="group-link__text">
                @lang("groups.view_link_polls") ({{ $group->num_open_polls }})
            </span>
        </a>
        <!-- Forum -->
        <a class="group-link @if (isset($page) && $page == 'forum') selected @endif" href="{{ route('group.forum', $group->slug) }}">
            <span class="group-link__icon">
                <i class="far fa-comments"></i>
            </span>
            <span class="group-link__text">
                @lang("groups.view_link_forum")
            </span>
        </a>
        <!--
        <a class="group-link" href="#">
            <span class="group-link__icon">
            </span>
            <span class="group-link__text">
            </span>
        </a>
        -->
    </div>
</div>
