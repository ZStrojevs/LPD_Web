<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Models\Item;

Route::get('/', function () {
    $items = Item::latest()->take(6)->get();
    return view('home', compact('items'));
})->name('home');

// Guest-only routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register']);

    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Resource routes for items except index and show
    Route::resource('items', ItemController::class)->except(['index', 'show']);

    // My items page
    Route::get('/items/my', [ItemController::class, 'myItems'])->name('items.my');
});

// Public items index route
Route::get('/items', [ItemController::class, 'index'])->name('items.index');