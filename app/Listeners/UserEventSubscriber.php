<?php

namespace App\Listeners;

use Users;
use FeedActivities;

class UserEventSubscriber
{
    public function subscribe($events)
    {
        // Task related events
        $events->listen('App\Events\Users\UserCreatedTask', 'App\Listeners\UserEventSubscriber@handleTaskCreated');
        $events->listen('App\Events\Users\UserUpdatedTask', 'App\Listeners\UserEventSubscriber@handleTaskUpdated');
        $events->listen('App\Events\Users\UserFollowsTask', 'App\Listeners\UserEventSubscriber@handleTaskFollowed');
        $events->listen('App\Events\Users\UserUnfollowsTask', 'App\Listeners\UserEventSubscriber@handleTaskUnfollowed');

        // Project related events
        $events->listen('App\Events\Users\UserCreatedProject', 'App\Listeners\UserEventSubscriber@handleProjectCreated');
        $events->listen('App\Events\Users\UserUpdatedProject', 'App\Listeners\UserEventSubscriber@handleProjectUpdated');
        $events->listen('App\Events\Users\UserFollowsProject', 'App\Listeners\UserEventSubscriber@handleProjectFollowed');
        $events->listen('App\Events\Users\UserUnfollowsProject', 'App\Listeners\UserEventSubscriber@handleProjectUnfollowed');

        // User related events
        $events->listen('App\Events\Users\UserFollowsUser', 'App\Listeners\UserEventSubscriber@handleUserFollowed');
        $events->listen('App\Events\Users\UserUnfollowsUser', 'App\Listeners\UserEventSubscriber@handleUserUnfollowed');
        $events->listen('App\Events\Users\UserLeftComment', 'App\Listeners\UserEventSubscriber@handleCommentLeft');
        $events->listen('App\Events\Users\UserUpdatedProfile', 'App\Listeners\UserEventSusbcriber@handleUpdatedProfile');
    }
    
    public function handleTaskCreated($event)
    {
        // Create an activity feed entry for all the user's followers
        foreach ($event->user->followers as $subscriber)
        {
            FeedActivities::create("user_created_task", $event->task, $event->user);
        }
    }   

    public function handleTaskUpdated($event)
    {
        // Create an activity feed entry for all the user's followers
        foreach ($event->user->followers as $subscriber)
        {
            FeedActivities::create("user_updated_task", $event->task, $event->user);
        }
    }

    public function handleTaskFollowed($event)
    {
        // Create an activity feed entry for all the user's followers
        foreach ($event->user->followers as $subscriber)
        {
            FeedActivities::create("user_followed_task", $event->task, $event->user);
        }
    }   

    public function handleTaskUnfollowed($event)
    {
        
    }

    public function handleProjectCreated($event)
    {
        // Create an activity feed entry for all the user's followers
        foreach ($event->user->followers as $subscriber)
        {
            FeedActivities::create("user_created_project", $event->project, $event->user);
        }
    }

    public function handleProjectUpdated($event)
    {
        // Create an activity feed entry for all the user's followers
        foreach ($event->user->followers as $subscriber)
        {
            FeedActivities::create("user_updated_project", $event->project, $event->user);
        }
    }

    public function handleProjectFollowed($event)
    {
        // Create an activity feed entry for all the user's followers
        foreach ($event->user->followers as $subscriber)
        {
            FeedActivities::create("user_followed_project", $event->project, $event->user);
        }
    }

    public function handleProjectUnfollowed($event)
    {

    }

    public function handleUserFollowed($event)
    {
        // Create an activity feed entry for all the user's followers
        foreach ($event->user->followers as $subscriber)
        {
            FeedActivities::create("user_followed_user", $event->targetUser, $event->user);
        }
    }

    public function handleUserUnfollowed($event)
    {
        
    }

    public function handleCommentLeft($event)
    {
        // Create an activity feed entry for all the user's followers
        foreach ($event->user->followers as $subscriber)
        {
            FeedActivities::create("user_commented", $event->comment, $event->user);
        }
    }
    
    public function handleUpdatedProfile($event)
    {
        // Create an activity feed entry for all the user's followers
        foreach ($event->user->followers as $subscriber)
        {
            FeedActivities::create("user_updated_profile", $event->user);
        }
    }
}