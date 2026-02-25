<?php

use App\Http\Controllers\Api\UserPlacesController;
use App\Http\Controllers\Api\WeatherApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Weather endpoints
    Route::get('/weather', [WeatherApiController::class, 'current']);
    Route::get('/forecast', [WeatherApiController::class, 'forecast']);

    // User city management
    // /favorite/* must come BEFORE /{place} to avoid route collision
    Route::prefix('user/places')->group(function () {
        Route::get('/favorite/weather', [UserPlacesController::class, 'favoriteWeather']);
        Route::get('/favorite/forecast', [UserPlacesController::class, 'favoriteForecast']);

        Route::get('/', [UserPlacesController::class, 'index']);
        Route::post('/', [UserPlacesController::class, 'store']);

        Route::delete('/{place}', [UserPlacesController::class, 'destroy']);
        Route::patch('/{place}/daily-report', [UserPlacesController::class, 'toggleDailyReport']);
        Route::patch('/{place}/favorite', [UserPlacesController::class, 'toggleFavorite']);
    });
});
