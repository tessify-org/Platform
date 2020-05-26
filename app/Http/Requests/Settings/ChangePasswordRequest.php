<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            "password" => "required",
            "new_password" => "required|confirmed",
            "new_password_confirmation" => "required",
        ];
    }

    /**
     * Get the validation messages for each rule
     * 
     * @return array
     */
    public function messages()
    {
        return [
            "password.required" => __("settings.change_password_current_password_required"),
            "new_password.required" => __("settings.change_password_new_password_required"),
            "new_password_confirmation.required" => __("settings.change_password_confirm_new_password_required"),
        ];
    }
}
