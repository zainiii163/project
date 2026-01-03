<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    public function switchLanguage($locale)
    {
        $supportedLocales = ['en', 'es', 'fr', 'de', 'ar', 'zh', 'ja', 'pt', 'ru', 'hi'];
        
        if (in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }

        return back();
    }

    public function setUserLanguage(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|in:en,es,fr,de,ar,zh,ja,pt,ru,hi',
        ]);

        if (auth()->check()) {
            auth()->user()->update(['preferred_language' => $validated['locale']]);
        }

        App::setLocale($validated['locale']);
        Session::put('locale', $validated['locale']);

        return back()->with('success', 'Language updated successfully!');
    }
}

