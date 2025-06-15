<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocaleFromBrowser
{
    public function handle($request, Closure $next)
    {
        $availableLocales = ['en', 'lv', 'ru'];
        $locale = config('app.fallback_locale', 'en');

        // Detect browser language
        $acceptLanguage = $request->server('HTTP_ACCEPT_LANGUAGE');
        if ($acceptLanguage) {
            $languages = explode(',', $acceptLanguage);
            foreach ($languages as $lang) {
                $lang = trim(explode(';', $lang)[0]);
                $langCode = substr($lang, 0, 2);
                if (in_array($langCode, $availableLocales)) {
                    $locale = $langCode;
                    break;
                }
            }
        }

        // If user has selected language before, override
        $sessionLocale = $request->session()->get('locale');
        if ($sessionLocale && in_array($sessionLocale, $availableLocales)) {
            $locale = $sessionLocale;
        }

        App::setLocale($locale);

        return $next($request);
    }
}