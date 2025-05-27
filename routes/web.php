<?php

use App\Http\Controllers\CallbackController;
use App\Http\Controllers\ProductIndexController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


Route::get('redirect', RedirectController::class)
    ->name('redirect')
    ->middleware('auth');

Route::get('callback', CallbackController::class)
    ->name('callback')
    ->middleware('auth');

Route::get('products', ProductIndexController::class)
    ->name('products.index')
    ->middleware('auth');


require __DIR__ . '/auth.php';
