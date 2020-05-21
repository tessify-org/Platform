<?php

namespace App\Http\Requests\Groups\Roles;

use Illuminate\Foundation\Http\FormRequest;

class DeleteGroupRoleRequest extends FormRequest
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
        ];
    }
}
