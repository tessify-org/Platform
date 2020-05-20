<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Commands\ReindexCommand;
use App\Services\Utilities\DateService;
use App\Services\Utilities\UploadService;
use App\Services\Utilities\SearchService;
use App\Services\Utilities\ReputationService;
use App\Services\ModelServices\UserService;
use App\Services\ModelServices\TaskService;
use App\Services\ModelServices\SkillService;
use App\Services\ModelServices\ProjectService;
use App\Services\ModelServices\CommentService;
use App\Services\ModelServices\TeamRoleService;
use App\Services\ModelServices\TaskStatusService;
use App\Services\ModelServices\TaskCategoryService;
use App\Services\ModelServices\TaskSeniorityService;
use App\Services\ModelServices\TeamMemberService;
use App\Services\ModelServices\WorkMethodService;
use App\Services\ModelServices\ProjectStatusService;
use App\Services\ModelServices\ProjectCategoryService;
use App\Services\ModelServices\ProjectResourceService;
use App\Services\ModelServices\TeamMemberApplicationService;
use App\Services\ModelServices\AssignmentService;
use App\Services\ModelServices\AssignmentTypeService;
use App\Services\ModelServices\MinistryService;
use App\Services\ModelServices\OrganizationService;
use App\Services\ModelServices\OrganizationTypeService;
use App\Services\ModelServices\OrganizationLocationService;
use App\Services\ModelServices\OrganizationDepartmentService;
use App\Services\ModelServices\NotificationService;
use App\Services\ModelServices\MessageService;
use App\Services\ModelServices\TaskProgressReportService;
use App\Services\ModelServices\TaskProgressReportReviewService;
use App\Services\ModelServices\TaskProgressReportAttachmentService;
use App\Services\ModelServices\ReputationTransactionService;
use App\Services\ModelServices\BugReportService;
use App\Services\ModelServices\CompletedTaskService;
use App\Services\ModelServices\ViewEmailRequestService;
use App\Services\ModelServices\NewsletterService;
use App\Services\ModelServices\ProjectPhaseService;
use App\Services\ModelServices\TagService;
use App\Services\ModelServices\FeedActivityService;
use App\Services\ModelServices\ReviewRequestService;
use App\Services\ModelServices\ReviewService;
use App\Services\ModelServices\WhitelistedDomainService;
use App\Services\ModelServices\FaqService;
use App\Services\ModelServices\FaqCategoryService;
use App\Services\ModelServices\FeedbackService;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        "App\Models\Task" => "App\Policies\TaskPolicy",
        "App\Models\Project" => "App\Policies\ProjectPolicy",
        "App\Models\TeamMemberApplication" => "App\Policies\TeamMemberApplicationPolicy",
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Define the authorization Gates
        $this->defineGates();

        // Register the package's policies
        $this->registerPolicies();

        // Compose views
        $this->composeViews();

        // Register CLI commands
        if ($this->app->runningInConsole())
        {
            $this->commands([
                ReindexCommand::class,
            ]);
        }
    }

    private function registerServices()
    {
        //
        // Register Model Services
        //

        $this->app->singleton("users", function() {
            return new UserService;
        });

        $this->app->singleton("skills", function() {
            return new SkillService;
        });

        $this->app->singleton("projects", function() {
            return new ProjectService;
        });

        $this->app->singleton("project-statuses", function() {
            return new ProjectStatusService;
        });

        $this->app->singleton("project-categories", function() {
            return new ProjectCategoryService;
        });

        $this->app->singleton("project-resources", function() {
            return new ProjectResourceService;
        });

        $this->app->singleton("project-phases", function() {
            return new ProjectPhaseService;
        });

        $this->app->singleton("work-methods", function() {
            return new WorkMethodService;
        });

        $this->app->singleton("tasks", function() {
            return new TaskService;
        });

        $this->app->singleton("task-statuses", function() {
            return new TaskStatusService;
        });

        $this->app->singleton("task-categories", function() {
            return new TaskCategoryService;
        });

        $this->app->singleton("task-seniorities", function() {
            return new TaskSeniorityService;
        });

        $this->app->singleton("team-members", function() {
            return new TeamMemberService;
        });

        $this->app->singleton("team-member-applications", function() {
            return new TeamMemberApplicationService;
        });

        $this->app->singleton("team-roles", function() {
            return new TeamRoleService;
        });

        $this->app->singleton("comments", function() {
            return new CommentService;
        });

        $this->app->singleton("assignments", function() {
            return new AssignmentService;
        });

        $this->app->singleton("assignment-types", function() {
            return new AssignmentTypeService;
        });
        
        $this->app->singleton("ministries", function() {
            return new MinistryService;
        });
        
        $this->app->singleton("organizations", function() {
            return new OrganizationService;
        });
        
        $this->app->singleton("organization-types", function() {
            return new OrganizationTypeService;
        });
        
        $this->app->singleton("organization-departments", function() {
            return new OrganizationDepartmentService;
        });

        $this->app->singleton("organization-locations", function() {
            return new OrganizationLocationService;
        });

        $this->app->singleton("notifications", function() {
            return new NotificationService;
        });

        $this->app->singleton("messages", function() {
            return new MessageService;
        });

        $this->app->singleton("task-progress-reports", function() {
            return new TaskProgressReportService;
        });

        $this->app->singleton("task-progress-report-reviews", function() {
            return new TaskProgressReportReviewService;
        });

        $this->app->singleton("task-progress-report-attachments", function() {
            return new TaskProgressReportAttachmentService;
        });

        $this->app->singleton("reputation-transactions", function() {
            return new ReputationTransactionService;
        });

        $this->app->singleton("bug-reports", function() {
            return new BugReportService;
        });

        $this->app->singleton("completed-tasks", function() {
            return new CompletedTaskService;
        });

        $this->app->singleton("view-email-requests", function() {
            return new ViewEmailRequestService;
        });

        $this->app->singleton("newsletters", function() {
            return new NewsletterService;
        });

        $this->app->singleton("tags", function() {
            return new TagService;
        });

        $this->app->singleton("feed-activities", function() {
            return new FeedActivityService;
        });

        $this->app->singleton("review-requests", function() {
            return new ReviewRequestService;
        });

        $this->app->singleton("reviews", function() {
            return new ReviewService;
        });

        $this->app->singleton("whitelisted-domains", function() {
            return new WhitelistedDomainService;
        });

        $this->app->singleton("faqs", function() {
            return new FaqService;
        });

        $this->app->singleton("faq-categories", function() {
            return new FaqCategoryService;
        });

        $this->app->singleton("feedback", function() {
            return new FeedbackService;
        });

        //
        // Utilities
        //

        $this->app->singleton("dates", function() {
            return new DateService;
        });

        $this->app->singleton("uploader", function() {
            return new UploadService;
        });

        $this->app->singleton("reputation", function() {
            return new ReputationService;
        });

        $this->app->singleton("search", function() {
            return new SearchService;
        });
    }

    private function defineGates()
    {
        Gate::define("access-admin-panel", function($user) {
            return $user->is_admin;
        });
    }

    private function registerPolicies()
    {
        foreach ($this->policies as $key => $value)
        {
            Gate::policy($key, $value);
        }
    }

    private function composeViews()
    {
        View::composer("layouts.app", function($view) {
            $view->with("user", Auth::user());
            $view->with("locales", config("platform.locales"));
            $view->with("activeLocale", app()->getLocale());
            $view->with("numUnreadNotifications", app("notifications")->numUnread());
            $view->with("numUnreadMessages", app("messages")->numUnread());
            $view->with("feedbackStrings", collect([
                "title" => __("feedback.dialog_title"),
                "general" => __("feedback.dialog_general_tab"),
                "gebruikerspanel" => __("feedback.dialog_gebruikerspanel_tab"),
                "type" => __("feedback.dialog_type"),
                "type_feedback" => __("feedback.dialog_type_feedback"),
                "type_bug" => __("feedback.dialog_type_bug"),
                "subject" => __("feedback.dialog_subject"),
                "page_url" => __("feedback.dialog_page_url"),
                "severity" => __("feedback.dialog_severity"),
                "severity_low" => __("feedback.dialog_severity_low"),
                "severity_medium" => __("feedback.dialog_severity_medium"),
                "severity_high" => __("feedback.dialog_severity_high"),
                "description" => __("feedback.dialog_description"),
                "description_bug" => __("feedback.dialog_description_bug"),
                "cancel" => __("feedback.dialog_cancel"),
                "submit" => __("feedback.dialog_submit"),
            ]));
        });

        View::composer("layouts.admin", function($view) {
            $view->with("user", Auth::user());
        });
    }
}
