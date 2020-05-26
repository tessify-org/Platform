<?php


namespace App\Http\Controllers\Settings;

use Users;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ChangePasswordRequest;

class SettingsController extends Controller
{
    public function getSettings()
    {
        return view("pages.settings.overview");
    }

    public function getChangePassword()
    {
        return view("pages.settings.change-password", [
            "strings" => collect([
                "current_password" => __("settings.change_password_current_password"),
                "new_password" => __("settings.change_password_new_password"),
                "confirm_new_password" => __("settings.change_password_confirm_new_password"),
                "cancel" => __("settings.change_password_cancel"),
                "submit" => __("settings.change_password_submit"),
            ]),
        ]);
    }

    public function postChangePassword(ChangePasswordRequest $request)
    {
        // Process the change password request
        Users::changePasswordFromRequest($request);

        // Flash message & redirect back to change password page
        flash(__("settings.password_changed"))->success();
        return redirect()->route("settings.change-password");
    }
}