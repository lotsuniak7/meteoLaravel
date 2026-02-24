<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\ForecastResource;
use App\Http\Resources\WeatherResource;
use App\Models\City;
use App\Services\OpenWeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserPlacesController extends Controller
{
    public function __construct(private readonly OpenWeatherService $weather) {}

    // GET /api/v1/user/places
    public function index(Request $request): AnonymousResourceCollection
    {
        $cities = $request->user()
            ->cities()
            ->orderByDesc('is_favorite')
            ->orderBy('name')
            ->paginate(15);

        return CityResource::collection($cities);
    }

    // POST /api/v1/user/places   body: { "place": "Paris" }
    public function store(Request $request): JsonResponse|CityResource
    {
        $request->validate(['place' => 'required|string']);

        $geo = $this->weather->geo($request->input('place'));

        if (isset($geo['error'])) {
            return response()->json(['message' => 'City not found.'], 404);
        }

        $city = $request->user()->cities()->firstOrCreate(
            ['name' => $geo['name']],
            ['is_favorite' => false, 'daily_report' => false]
        );

        return (new CityResource($city))->response()->setStatusCode(201);
    }

    // DELETE /api/v1/user/places/{place}
    public function destroy(Request $request, City $place): JsonResponse
    {
        $this->authorize('delete', $place);
        $place->delete();

        return response()->json(['message' => 'City removed.']);
    }

    // PATCH /api/v1/user/places/{place}/daily-report
    public function toggleDailyReport(Request $request, City $place): CityResource
    {
        $this->authorize('update', $place);
        $place->update(['daily_report' => !$place->daily_report]);

        return new CityResource($place->fresh());
    }

    // PATCH /api/v1/user/places/{place}/favorite
    public function toggleFavorite(Request $request, City $place): CityResource
    {
        $this->authorize('update', $place);
        $user = $request->user();

        if ($place->is_favorite) {
            $place->update(['is_favorite' => false]);
        } else {
            $user->cities()->where('is_favorite', true)->update(['is_favorite' => false]);
            $place->update(['is_favorite' => true]);
        }

        return new CityResource($place->fresh());
    }

    // GET /api/v1/user/places/favorite/weather
    public function favoriteWeather(Request $request): JsonResponse|WeatherResource
    {
        $favorite = $request->user()->favoriteCity();

        if (!$favorite) {
            return response()->json(['message' => 'No favorite city set.'], 404);
        }

        $data = $this->weather->current($favorite->name);

        if (isset($data['error'])) {
            return response()->json(['message' => $data['error']], 502);
        }

        return new WeatherResource($data);
    }

    // GET /api/v1/user/places/favorite/forecast
    public function favoriteForecast(Request $request): JsonResponse|ForecastResource
    {
        $favorite = $request->user()->favoriteCity();

        if (!$favorite) {
            return response()->json(['message' => 'No favorite city set.'], 404);
        }

        $raw   = $this->weather->forecastByName($favorite->name);
        $daily = $this->weather->extractDailyForecast($raw);

        return new ForecastResource(['place' => $favorite->name, 'daily' => $daily]);
    }
}
