<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

//Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
//Route::post('/weather', [WeatherController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

// When user open dashboard
Route::get('/dashboard', [WeatherController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// POST when user send new city or clear
Route::post('/dashboard', [WeatherController::class, 'index'])
    ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
