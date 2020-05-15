<?php

namespace App\Http\Controllers\Translation;

use Session;
use App\Http\Controllers\Controller;
use App\Http\Requests\Translation\SwitchLocaleRequest;

class LocaleController extends Controller
{
    public function postSwitchLocale(SwitchLocaleRequest $request)
    {
        // Set the locale on the sessions
        Session::put(['active_locale' => $request->locale]);

        dd($request->locale, Session::get('active_locale'));

        // Return back to the previous page
        return redirect()->back();
    }
}