<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RentalRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

// Language switch route
Route::post('/language-switch', function (Request $request) {
    $lang = $request->input('language');
    $availableLocales = ['en', 'ru'];

    if (in_array($lang, $availableLocales)) {
        session(['locale' => $lang]);
        App::setLocale($lang);
        \Log::info('Language switched', [
            'requested_locale' => $lang,
            'session_locale' => session('locale'),
            'app_locale' => app()->getLocale(),
            'welcome_translation' => __('messages.welcome'),
            'messages_translations' => json_encode(trans('messages')),
            'lang_path' => lang_path(),
            'translation_exists' => Lang::has('messages.welcome') ? 'Yes' : 'No'
        ]);
    } else {
        \Log::info('Invalid locale attempted: ' . $lang);
    }
    return redirect()->back();
})->name('language.switch');

// Home page route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest routes (login, register)
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::resource('items', ItemController::class)->except(['index', 'show']);
    Route::get('/items/my', [ItemController::class, 'myItems'])->name('items.my');

    Route::get('/rental-requests/create/{item}', [RentalRequestController::class, 'create'])->name('rental-requests.create');
    Route::post('/rental-requests', [RentalRequestController::class, 'store'])->name('rental-requests.store');
    Route::get('/rental-requests', [RentalRequestController::class, 'index'])->name('rental-requests.index');
    Route::get('/rental-requests/my', [RentalRequestController::class, 'myRequests'])->name('rental-requests.my');
    Route::post('/rental-requests/{rentalRequest}/approve', [RentalRequestController::class, 'approve'])->name('rental-requests.approve');
    Route::post('/rental-requests/{rentalRequest}/reject', [RentalRequestController::class, 'reject'])->name('rental-requests.reject');
    Route::delete('/rental-requests/{rentalRequest}', [RentalRequestController::class, 'destroy'])->name('rental-requests.destroy');
    Route::post('/rental-requests/{rentalRequest}/cancel', [RentalRequestController::class, 'cancel'])->name('rental-requests.cancel');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Public routes for viewing items (no auth needed)
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// Admin routes with admin middleware & prefix
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Option 1: Dashboard shows main admin summary
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Option 2: Redirect dashboard to items list (uncomment if preferred)
    /*
    Route::get('/', function() {
        return redirect()->route('admin.items');
    })->name('dashboard');
    */

    Route::get('/items', [AdminController::class, 'items'])->name('items');
    Route::get('/items/{item}/edit', [AdminController::class, 'editItem'])->name('items.edit');
    Route::put('/items/{item}', [AdminController::class, 'updateItem'])->name('items.update');
    Route::delete('/items/{item}', [AdminController::class, 'deleteItem'])->name('items.delete');

    Route::delete('/rental-requests/{rentalRequest}', [AdminController::class, 'deleteRentalRequest'])->name('rental-requests.delete');
});


Route::get('/test-locale', function () {
    return [
        'current_locale' => app()->getLocale(),
        'accept_language' => request()->server('HTTP_ACCEPT_LANGUAGE'),
        'translation_test' => __('messages.welcome'),
    ];
});
Route::get('/test-translation', function () {
    return [
        'current_locale' => app()->getLocale(),
        'welcome_en' => __('messages.welcome'),
        'has_translation' => Lang::has('messages.welcome'),
        'all_messages' => trans('messages'),
    ];
});
Route::get('/debug-headers', function () {
    $request = request();
    
    return [
        'all_headers' => $request->headers->all(),
        'accept_language_header' => $request->header('Accept-Language'),
        'accept_language_server' => $request->server('HTTP_ACCEPT_LANGUAGE'),
        'current_locale' => app()->getLocale(),
        'available_locales' => ['en', 'lv', 'ru'],
        'user_agent' => $request->userAgent(),
    ];
});
Route::get('/middleware-test', function () {
    // Manually simulate what the middleware should do
    $acceptLanguage = request()->server('HTTP_ACCEPT_LANGUAGE');
    $availableLocales = ['en', 'lv', 'ru'];
    
    $locale = 'en';
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
    
    // Set locale manually
    app()->setLocale($locale);
    
    return [
        'accept_language' => $acceptLanguage,
        'detected_locale' => $locale,
        'current_locale' => app()->getLocale(),
        'welcome_message' => __('messages.welcome'),
        'config_locale' => config('app.locale'),
    ];
});