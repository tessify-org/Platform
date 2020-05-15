<?php

namespace App\Http\Requests\Admin\Users;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() and Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "is_admin" => "required",
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email",
            "password" => "nullable|confirmed",
            "password_confirmation" => "nullable",
            "headline" => "nullable",
            "interests" => "nullable",
        ];
    }
}
