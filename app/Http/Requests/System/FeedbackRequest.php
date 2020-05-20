<?php

namespace App\Http\Requests\System;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
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
            "target" => "required",
            "type" => "nullable",
            "subject" => "required",
            "page_url" => "required",
            "severity" => "nullable",
            "description" => "required",
        ];
    }
}
