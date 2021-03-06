<?php

namespace App\Http\Requests\Community\Groups\Members;

use Illuminate\Foundation\Http\FormRequest;

class KickGroupMemberRequest extends FormRequest
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
            "group_member_id" => "required|exists:group_members,id",
        ];
    }
}
