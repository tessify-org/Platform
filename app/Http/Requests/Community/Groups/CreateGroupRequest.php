<?php

namespace App\Http\Requests\Community\Groups;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupRequest extends FormRequest
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
            "name" => "required",
            "slogan_nl" => "nullable",
            "slogan_en" => "nullable",
            "description_nl" => "required",
            "description_en" => "nullable",
            "tags" => "nullable",
        ];
    }
}
