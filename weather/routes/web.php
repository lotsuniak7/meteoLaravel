<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ProfileController;

// Home redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [WeatherController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // Search form
    Route::post('/city/search', [CityController::class, 'search'])->name('city.search');

    // City detail page
    Route::get('/city/{city}', [CityController::class, 'show'])->name('city.show');

    // My cities list page
    Route::get('/cities', [CityController::class, 'index'])->name('cities.index');

    // Add city to list
    Route::post('/cities', [CityController::class, 'add'])->name('city.add');

    // Remove city from list
    Route::delete('/cities/{city}', [CityController::class, 'remove'])->name('city.remove');

    // Toggle favorite
    Route::patch('/cities/{city}/favorite', [CityController::class, 'toggleFavorite'])->name('city.favorite');

    // Toggle daily report
    Route::patch('/cities/{city}/daily-report', [CityController::class, 'toggleDailyReport'])->name('city.daily-report');

    // Exports
    Route::get('/cities/{city}/export/xlsx', [CityController::class, 'exportXlsx'])->name('city.export.xlsx');
    Route::get('/cities/{city}/export/csv',  [CityController::class, 'exportCsv'])->name('city.export.csv');

});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/token', [ProfileController::class, 'generateToken'])->name('profile.token.generate');
});

require __DIR__.'/auth.php';
