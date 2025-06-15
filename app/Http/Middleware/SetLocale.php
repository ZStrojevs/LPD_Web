<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    protected $availableLocales = ['en', 'lv', 'ru'];

    public function handle($request, Closure $next)
    {
        $sessionLocale = $request->session()->get('locale');
        $locale = config('app.fallback_locale', 'en');

        // Use session locale if valid
        if ($sessionLocale && in_array($sessionLocale, $this->availableLocales)) {
            $locale = $sessionLocale;
        } else {
            // Detect from browser Accept-Language header
            $acceptLanguage = $request->server('HTTP_ACCEPT_LANGUAGE');
            if ($acceptLanguage) {
                $languages = explode(',', $acceptLanguage);
                foreach ($languages as $lang) {
                    $lang = trim(explode(';', $lang)[0]);
                    $langCode = substr($lang, 0, 2);
                    if (in_array($langCode, $this->availableLocales)) {
                        $locale = $langCode;
                        break;
                    }
                }
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}
