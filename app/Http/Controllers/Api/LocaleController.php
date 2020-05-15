<?php

namespace App\Http\Controllers\Api;

use Session;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Translation\SetActiveLocaleRequest;

class LocaleController extends Controller
{
    public function postSetActiveLocale(SetActiveLocaleRequest $request)
    {
        // Set the locale on the sessions
        Session::put(['active_locale' => $request->locale]);

        // Return a JSON response
        return response()->json([
            "status" => "success",
            "active_locale" => $request->locale,
        ]);
    }
}