<?php

namespace App\Http\Requests\Community\Forums\Threads;

use Illuminate\Foundation\Http\FormRequest;

class CreateThreadRequest extends FormRequest
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
            "description" => "nullable",
            "message" => "required",
            "sticky" => "required",
            "closed" => "required",
        ];
    }
}
