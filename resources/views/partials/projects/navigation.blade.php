<div id="project-navigation__wrapper">
    <nav id="project-navigation" class="elevation-1">
        <ul>
            <!-- Information -->
            <li @if($page == 'info') class="active" @endif>
                <a href="{{ route('projects.view', $project->slug) }}">
                    @lang("projects.project_navigation_info")
                </a>
            </li>
            <!-- Team members -->
            <li @if($page == 'team') class="active" @endif>
                <a href="{{ route('projects.team.view', $project->slug) }}">
                    @lang("projects.project_navigation_team")
                </a>
            </li>
            <!-- Team applications -->
            <li @if($page == 'team-applications') class="active" @endif>
                <a href="{{ route('projects.team.applications', $project->slug) }}">
                    @lang("projects.project_navigation_applications")
                </a>
            </li>
            <!-- Tasks -->
            <li @if($page == 'tasks') class="active" @endif>
                <a href="{{ route('projects.tasks', $project->slug) }}">
                    @lang("projects.project_navigation_tasks")
                </a>
            </li>
        </ul>
    </nav>
</div>