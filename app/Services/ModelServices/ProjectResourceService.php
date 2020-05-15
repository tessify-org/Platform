<?php

namespace App\Services\ModelServices;

use Auth;
use Uploader;

use App\Models\Project;
use App\Models\ProjectResource;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Projects\CreateProjectResourcesRequest;
use App\Http\Requests\Api\Projects\Resources\UploadFilesRequest;
use App\Http\Requests\Api\Projects\Resources\CreateProjectResourceRequest;
use App\Http\Requests\Api\Projects\Resources\UpdateProjectResourceRequest;

class ProjectResourceService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\ProjectResource";
    }
    
    public function preload($instance)
    {
        // Convert file url from relative to absolute
        $instance->file_url = asset($instance->file_url);
        
        return $instance;
    }

    public function getAllPreloadedForProject(Project $project)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $resource)
        {
            if ($resource->project_id == $project->id)
            {
                $out[] = $resource;
            }
        }

        return $out;
    }

    public function createFromRequest(CreateProjectResourceRequest $request)
    {
        $user = Auth::user();

        $resource = ProjectResource::create([
            "project_id" => $request->project_id,
            "user_id" => $user->id,
            "title" => $request->title,
            "description" => $request->description,
            "file_url" => Uploader::upload($request->file("file"), "files/job_resources"),
            "file_size" => $request->file("file")->getSize(),
        ]);

        return $resource;
    }
    
    public function updateFromRequest(UpdateProjectResourceRequest $request)
    {
        $resource = $this->find($request->project_resource_id);
        if ($resource)
        {
            $resource->title = $request->title;
            $resource->description = $request->description;
    
            if ($request->hasFile("file"))
            {
                $resource->file_url = Uploader::upload($request->file("file"), "files/job_resources");
                $resource->file_size = $request->file("file")->getSize();
            }
            
            $resource->save();

            return $resource;
        }

        return false;
    }

    public function processMultipleUploadsForProject(Project $project, UploadFilesRequest $request)
    {
        $out = [];

        $user = auth()->user();

        foreach ($request->file("files") as $file)
        {
            $resource = ProjectResource::create([
                "project_id" => $project->id,
                "user_id" => $user->id,
                "file_type" => $file->getClientOriginalExtension(),
                "file_size" => $file->getSize(),
                "file_url" => Uploader::upload($file, "files/project/resources/".$project->id),
            ]);

            $resource->formatted_file_url = asset($resource->file_url);

            $out[] = $resource;
        }
        
        return $out;
    }

    public function postProcessResourceUploads(CreateProjectResourcesRequest $request)
    {
        $out = [];

        $resourceData = json_decode($request->resources);
        if (is_array($resourceData) && count($resourceData) > 0)
        {
            foreach ($resourceData as $entry)
            {
                $resource = $this->find($entry->id);
                
                $resource->title = isset($entry->title) ? $entry->title : "Untitled resource";
                $resource->description = isset($entry->description) ? $entry->description : null;
                $resource->save();

                $out[] = $resource;
            }
        }

        return $out;
    }
}