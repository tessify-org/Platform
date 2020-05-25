<?php

namespace App\Http\Requests\Community\Polls;

use Illuminate\Foundation\Http\FormRequest;

class VotePollRequest extends FormRequest
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
            "poll_id" => "required|exists:polls,id",
            "answers" => "required",
        ];
    }
}
