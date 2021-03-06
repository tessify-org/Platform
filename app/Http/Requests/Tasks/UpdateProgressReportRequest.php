<?php

namespace App\Http\Requests\Tasks;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProgressReportRequest extends FormRequest
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
            "message" => "required",
            "hours" => "required|integer",
            "completed" => "nullable",
        ];
    }
}
