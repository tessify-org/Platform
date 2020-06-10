<?php

namespace App\Http\Requests\Tasks;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
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
            "project_id" => "nullable",
            "group" => "nullable",
            "organization" => "nullable",
            "department" => "nullable",
            "task_category" => "required",
            "task_seniority_id" => "required|exists:task_seniorities,id",
            "title_nl" => "required",
            "title_en" => "required",
            "description_nl" => "required",
            "description_en" => "required",
            "complexity" => "required|integer",
            "estimated_hours" => "required|integer",
            "urgency" => "required|integer",
            "required_skills" => "nullable",
            "tags" => "nullable",
            "has_deadline" => "nullable",
            "ends_at" => "nullable",
            "header_image" => "nullable|image",
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
            "title_nl.required" => __("tasks.title_nl_required"),
            "title_en.required" => __("tasks.title_en_required"),
            "description_nl.required" => __("tasks.description_nl_required"),
            "description_en.required" => __("tasks.description_en_required"),
            "task_category.required" => __("tasks.category_required"),
        ];
    }
}
