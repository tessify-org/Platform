<?php

namespace App\Services\ModelServices;

use App\Models\Project;
use App\Models\ProjectPhase;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class ProjectPhaseService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\ProjectPhase";
    }

    public function preload($instance)
    {
        return $instance;
    }

    public function findOrCreateByLabel($label)
    {
        foreach ($this->getAll() as $phase)
        {
            if ($phase->label == $label)
            {
                return $phase;
            }
        }

        return ProjectPhase::create([
            "name" => strtolower($label),
            "label" => [
                "en" => $label,
                "nl" => $label,
            ]
        ]);
    }

    public function findOrCreateByName($name)
    {
        foreach ($this->getAll() as $phase)
        {
            if ($phase->name == $name)
            {
                return $phase;
            }
        }

        return ProjectPhase::create([
            "name" => $name,
            "label" => [
                "en" => ucfirst(strtolower($name)),
                "nl" => ucfirst(strtolower($name)),
            ]
        ]);
    }

    public function findForProject(Project $project)
    {
        foreach ($this->getAllPreloaded() as $phase)
        {
            if ($phase->id == $project->project_phase_id)
            {
                return $phase;
            }
        }

        return false;
    }
}
    