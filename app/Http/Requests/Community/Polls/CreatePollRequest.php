<?php

namespace App\Http\Requests\Community\Polls;

use Illuminate\Foundation\Http\FormRequest;

class CreatePollRequest extends FormRequest
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
            "title" => "required",
            "description_nl" => "required",
            "description_en" => "required",
            "draft" => "required",
            "private" => "required",
            "questions" => "required",
            "header_image" => "nullable|image",
        ];
    }
}
