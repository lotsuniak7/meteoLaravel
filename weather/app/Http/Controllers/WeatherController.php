<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index(Request $request, OpenWeatherService $weatherService)
    {
        // get the main city from the session or use dijon if its empty
        $mainCityName = $request->session()->get('weather_main', 'Dijon');
        // take the list of favorite cities from session if we have any stored
        $favoriteCityNames = $request->session()->get('weather_favorites', []);

        // fetch current weather data for the main city from the service
        $mainCityData = $weatherService->current($mainCityName);

        // checking if we actually got the json back from the api
        // dd($mainCityData);

        // check if the api returned an error like a bad key or wrong city name
        // and reset to dijon if something broke so the site doesnt crash
        if (isset($mainCityData['error'])) {
            $mainCityName = 'Dijon';
            $request->session()->put('weather_main', 'Dijon');
            $mainCityData = $weatherService->current($mainCityName);
        }

        // get the hourly forecast
        $hourly = [];
        if (isset($mainCityData['coord'])) {
            // get the rawForecast, thats mean the data unreadable, date - 1661871600, or temp - 296.76
            $rawForecast = $weatherService->forecast($mainCityData['coord']['lat'], $mainCityData['coord']['lon']);

            // looking at the forecast json to see the structure of raw data
            // dd($rawForecast);

            // use function of service to change raw data to normal
            $extracted = $weatherService->extractHourlyForecast($rawForecast);

            // merge today and tomorrow data
            $hourly = array_merge($extracted['today'] ?? [], $extracted['tomorrow'] ?? []);
        }

        // put everything together in one array for the main view
        $mainWeather = [
            'name' => $mainCityName,
            'data' => $mainCityData,
            'hourly' => $hourly
        ];

        // all the favorite cities
        $favoritesData = [];
        foreach ($favoriteCityNames as $city) {
            $current = $weatherService->current($city);

            // checking if the loop is getting data for this specific city
            // dd($current);

            // add it to the list if the api call was successful
            if (!isset($current['error'])) {
                $favoritesData[] = [
                    'name' => $city,
                    'temp' => round($current['main']['temp']),
                    'icon' => $current['weather'][0]['icon'] ?? null,
                    'description' => $current['weather'][0]['description'] ?? '',
                ];
            }
        }

        // send all the weather data to the dashboard view
        return view('dashboard', [
            'mainWeather' => $mainWeather,
            'favorites' => $favoritesData
        ]);
    }
}
