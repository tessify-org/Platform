<?php

namespace App\Http\Requests\Projects;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "project_status_id" => "required|exists:project_statuses,id",
            "project_category" => "required",
            "project_phase" => "nullable",
            "project_code" => "nullable",
            "ministry_id" => "nullable|exists:ministries,id",
            "organization_id" => "nullable|exists:organizations,id",
            "department" => "nullable",
            "title" => "required",
            "slogan_nl" => "nullable",
            "slogan_en" => "nullable",
            "description_nl" => "required",
            "description_en" => "required",
            "header_image" => "nullable|image",
            "starts_at" => "nullable",
            "ends_at" => "nullable",
            "has_deadline" => "required",
            "budget" => "nullable",
            "tags" => "nullable",
        ];
    }

    /**
     * Get the validation messages per rule
     * 
     * @return array
     */
    public function messages()
    {
        return [
            "description_nl.required" => __("projects.description_nl_required"),
            "description_en.required" => __("projects.description_en_required"),
            "ministry_id.exists" => __("projects.ministry_invalid"),
        ];
    }
}
