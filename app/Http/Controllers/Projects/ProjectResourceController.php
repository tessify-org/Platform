<?php

namespace App\Http\Controllers\Projects;

use Projects;
use ProjectResources;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateProjectResourcesRequest;
use App\Http\Requests\Api\Projects\Resources\UploadFilesRequest;

class ProjectResourceController extends Controller
{
    public function getOverview($slug)
    {
        // Grab the project we want to complete
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Render the project review overview page
        return view("pages.projects.resources.overview", [
            "project" => $project,
            "resources" => collect(ProjectResources::getAllPreloadedForProject($project)),
            "strings" => collect([
                "no_records" => __("projects.no_resources"),
                "add_button" => __("projects.add_resources"),
                "view_dialog_title" => __("projects.resources_view_dialog_title"),
                "view_dialog_download" => __("projects.resources_view_dialog_download"),
                "view_dialog_edit" => __("projects.resources_view_dialog_edit"),
                "view_dialog_delete" => __("projects.resources_view_dialog_delete"),
                "edit_dialog_title" => __("projects.resources_edit_dialog_title"),
                "form_title" => __("projects.resources_form_title"),
                "form_description" => __("projects.resources_form_description"),
                "edit_dialog_cancel" => __("projects.resources_edit_dialog_cancel"),
                "edit_dialog_submit" => __("projects.resources_edit_dialog_submit"),
                "delete_dialog_title" => __("projects.resources_delete_dialog_title"),
                "delete_dialog_text" => __("projects.resources_delete_dialog_text"),
                "delete_dialog_cancel" => __("projects.resources_delete_dialog_cancel"),
                "delete_dialog_submit" => __("projects.resources_delete_dialog_submit"),
            ]),
            "apiEndpoints" => collect([
                "update_resource" => route("api.projects.resources.update.post"),
                "delete_resource" => route("api.projects.resources.delete.post"),
            ]),
        ]);
    }

    public function getCreate($slug)
    {
        // Grab the project we want to complete
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        return view("pages.projects.resources.create", [
            "project" => $project,
            "strings" => collect([
                "upload_title" => __("projects.resources_create_upload_title"),
                "upload_subtitle" => __("projects.resources_create_upload_subtitle"),
                "form_file" => __("projects.resources_create_form_file"),
                "form_title" => __("projects.resources_create_form_title"),
                "form_description" => __("projects.resources_create_form_description"),
                "back" => __("projects.resources_create_back"),
                "submit" => __("projects.resources_create_submit"),
                "uploading_title" => __("projects.resources_create_uploading_title"),
                "uploading_files" => __("projects.resources_create_uploading_files"),
                "uploading_file" => __("projects.resources_create_uploading_file"),
            ]),
            "apiEndpoints" => collect([
                "upload" => route("projects.resources.upload.post", $project->slug),
            ])
        ]);
    }
    
    public function postCreate(CreateProjectResourcesRequest $request, $slug)
    {
        // Grab the project we want to complete
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        // Post-process the resources (update their title & description field)
        $resources = ProjectResources::postProcessResourceUploads($request);

        // Flash message & redirect back to view project page
        flash(__("projects.resources_created", ["count" => count($resources)]))->success();
        return redirect()->route("projects.resources", $project->slug);
    }
}