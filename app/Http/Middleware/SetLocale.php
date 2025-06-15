<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $sessionLocale = session('locale', config('app.locale'));
        $appDefaultLocale = config('app.locale');
        
        \Log::info('SetLocale middleware - BEFORE setting locale', [
            'session_locale' => $sessionLocale,
            'app_default_locale' => $appDefaultLocale,
            'current_locale_before' => app()->getLocale(),
            'available_locales' => ['en', 'ru'],
            'is_valid_locale' => in_array($sessionLocale, ['en', 'ru']),
        ]);
        
        // Set the locale
        if (in_array($sessionLocale, ['en', 'ru'])) {
            App::setLocale($sessionLocale);
            \Log::info('Setting locale to: ' . $sessionLocale);
        } else {
            App::setLocale($appDefaultLocale);
            \Log::info('Setting locale to default: ' . $appDefaultLocale);
        }
        
        // Debug AFTER setting locale
        \Log::info('SetLocale middleware - AFTER setting locale', [
            'session_locale' => session('locale'),
            'current_locale_after' => app()->getLocale(),
            'welcome_translation' => __('messages.welcome'),
            'lang_path' => lang_path(),
            'translation_exists' => Lang::has('messages.welcome') ? 'Yes' : 'No',
            'ru_translation_exists' => Lang::has('messages.welcome', 'ru') ? 'Yes' : 'No',
            'en_translation_exists' => Lang::has('messages.welcome', 'en') ? 'Yes' : 'No',
        ]);
        
        return $next($request);
    }
}