<?php

namespace App\Http\Requests\Community\Groups\Roles;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "group_role_id" => "required|exists:group_roles,id",
            "name_nl" => "required",
            "name_en" => "nullable",
            "description_nl" => "nullable",
            "description_en" => "nullable",
        ];
    }
}
