<?php

namespace App\Http\Requests\Groups\Applications;

use Illuminate\Foundation\Http\FormRequest;

class RejectGroupMemberApplicationRequest extends FormRequest
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
            "group_member_application_id" => "required|exists:group_member_applications,id",
        ];
    }
}
