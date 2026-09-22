<?php

namespace App\Core\Language\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        $supported = config('app.supported_locales', ['en', 'vi']);

        if (!in_array($locale, $supported)) {
            abort(400, 'Unsupported locale');
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'locale' => $locale]);
        }

        return back();
    }

    public function current(Request $request)
    {
        return response()->json([
            'locale' => App::getLocale(),
            'supported' => config('app.supported_locales', ['en', 'vi']),
        ]);
    }
}
