<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    // our main page
    public function index(Request $request, OpenWeatherService $weatherService)
    {
        // Dijon like default city
        $cities = $request->session()->get('weather_cities', ['Dijon']);

        // here we will store all our weather data
        $weatherDataList = [];
        $error = null;

        // POST request
        // Here we check if is a post
        if ($request->isMethod('post')) {
            // It is 'Clear'?
            if ($request->input('action') === 'clear') {
                // Reset to default
                $cities = ['Dijon'];
                $request->session()->put('weather_cities', $cities); // we put this

                // return to the same page
                return redirect()->route('dashboard');
            }


            // Message if we not put a city to the input
            $newCity = $request->input('city');
            if (empty($newCity)) {
                $error = 'Please enter a city';
            } else {
                $checkWeather = $weatherService->current($newCity);

                // If result does not exist we show an error
                if (isset($checkWeather['error']) || (isset($checkWeather['cod']) && $checkWeather['cod'] == '404')) {
                    $error = $checkWeather['error'] ?? 'City not found';
                } else {
                    $cityLower = strtolower($newCity);

                    // Romove a city from the list, if city already exist and to move him to the front
                    $cities = array_filter($cities, function ($city) use ($cityLower) {
                        return strtolower($city) !== $cityLower;
                    });

                    // add a city to the begining of list
                    array_unshift($cities, $newCity);
                    // Save the new list to the data
                    $request->session()->put('weather_cities', $cities);
                }
            }
        }

        // Get weather data
        $weatherDataList = [];

        // Get data from the cities via loop (foreach)
        foreach ($cities as $city) {
            $current = $weatherService->current($city);

            // Check if the API call was successful
            if (isset($current['error'])) {
                $weatherDataList[] = [
                    'city' => $city,
                    'error' => $current['error']
                ];
                continue; // to next city
            }

            // forecast
            $lat = $current['coord']['lat'];
            $lon = $current['coord']['lon'];
            $forecast = $weatherService->forecast($lat, $lon);

            // Add all data to our list
            $weatherDataList[] = [
                'city' => $city,
                'current' => $current,
                'forecast' => $forecast,
                'error' => null
            ];
        }


        return view('dashboard', [
            'weatherDataList' => $weatherDataList,
            'error' => $error
        ]);
    }
}
