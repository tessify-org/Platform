<?php

namespace App\Http\Requests\Api\Assignments;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class DeleteAssignmentRequest extends FormRequest
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
            "assignment_id" => "required|exists:assignments,id",
        ];
    }
}
