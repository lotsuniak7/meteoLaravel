<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ForecastResource;
use App\Http\Resources\WeatherResource;
use App\Services\OpenWeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeatherApiController extends Controller
{
    public function __construct(private readonly OpenWeatherService $weather) {}

    // GET /api/v1/weather?place=Paris
    public function current(Request $request): JsonResponse|WeatherResource
    {
        $place = $request->query('place');

        if (!$place) {
            return response()->json(['message' => 'The place parameter is required.'], 422);
        }

        $data = $this->weather->current($place);

        if (isset($data['error'])) {
            return response()->json(['message' => $data['error']], 404);
        }

        return new WeatherResource($data);
    }

    // GET /api/v1/forecast?place=Paris
    public function forecast(Request $request): JsonResponse|ForecastResource
    {
        $place = $request->query('place');

        if (!$place) {
            return response()->json(['message' => 'The place parameter is required.'], 422);
        }

        $raw = $this->weather->forecastByName($place);

        if (isset($raw['error'])) {
            return response()->json(['message' => $raw['error']], 404);
        }

        $daily = $this->weather->extractDailyForecast($raw);

        return new ForecastResource(['place' => $place, 'daily' => $daily]);
    }
}
