<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WeatherController extends Controller
{
    public function index(Request $request, OpenWeatherService $weatherService)
    {
        $user = Auth::user();
        $cities = $user->cities()->orderByDesc('is_favorite')->orderBy('name')->get();
        $favorite = $cities->firstWhere('is_favorite', true);
        $mainCityName = $favorite?->name ?? $cities->first()?->name ?? 'Dijon';

        // fetch current weather data for the main city from the service
        $mainCityData = $weatherService->current($mainCityName);

        // checking if we actually got the json back from the api
        // dd($mainCityData);

        // check if the api returned an error like a bad key or wrong city name
        // and reset to dijon if something broke so the site doesnt crash
        if (isset($mainCityData['error'])) {
            $mainCityName = 'Dijon';
            $mainCityData = $weatherService->current($mainCityName);
        }

        // get the hourly forecast
        $hourly = [];
        if (isset($mainCityData['coord'])) {
            // get the rawForecast, thats mean the full data all days
            $rawForecast = $weatherService->forecast($mainCityData['coord']['lat'], $mainCityData['coord']['lon']);

            //-------------------------3-------------------------
            // looking at the forecast json to see the structure of raw data
            //dd($rawForecast);

            // use function of service to change raw data to normal
            $extracted = $weatherService->extractHourlyForecast($rawForecast);

            // merge today and tomorrow data
            $hourly = array_merge($extracted['today'] ?? [], $extracted['tomorrow'] ?? []);
            //-------------------------5-------------------------
            // dd($hourly);
        }


        // put everything together in one array for the main view
        $mainWeather = [
            'name' => $mainCityName,
            'data' => $mainCityData,
            'hourly' => $hourly
        ];

        // Fetch brief weather summary for every other saved city
        $savedCitiesData = [];
        foreach ($cities as $city) {
            if (strtolower($city->name) === strtolower($mainCityName)) {
                continue;
            }

            $current = $weatherService->current($city->name);

            // checking if the loop is getting data for this specific city
            // dd($current);

            // add it to the list if the api call was successful
            if (!isset($current['error'])) {
                $savedCitiesData[] = [
                    'name' => $city->name,
                    'temp' => round($current['main']['temp']),
                    'icon' => $current['weather'][0]['icon'] ?? null,
                    'description' => $current['weather'][0]['description'] ?? '',
                    'is_favorite' => $city->is_favorite,
                    'daily_report'=> $city->daily_report,
                ];
            }
        }

        // send all the weather data to the dashboard view
        return view('dashboard', [
            'mainWeather' => $mainWeather,
            'favorites' => $savedCitiesData
        ]);
    }
}
