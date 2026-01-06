<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    // SEARCH LOGIC
    // it takes the city name and redirects the user to the proper detail page url
    public function search(Request $request)
    {
        $city = $request->input('city');

        // if they pressed enter without typing anything
        if (empty($city)) {
            return redirect()->back()->with('error', 'Please enter a city name');
        }

        // redirecting to the show method below
        return redirect()->route('city.show', ['city' => $city]);
    }

    // DETAILED CITY PAGE
    // shows current weather and daily forecast for a specific city
    public function show($city, OpenWeatherService $weatherService)
    {
        // verify if the city actually exists by asking the api
        $current = $weatherService->current($city);

        // if the api says city not found, back user to dashboard with an error
        if (isset($current['error'])) {
            return redirect()->route('dashboard')->with('error', 'City not found: ' . $city);
        }

        // fetching the forecast because i need the daily summary for this page
        $dailyForecast = [];
        if (isset($current['coord'])) {
            $rawForecast = $weatherService->forecast($current['coord']['lat'], $current['coord']['lon']);
            // using my helper to get simple day-by-day stats
            $dailyForecast = $weatherService->extractDailyForecast($rawForecast);
        }

        // i need to know if this city is already the main one or in favorites
        $mainCity = session('weather_main', 'Dijon');
        $favorites = session('weather_favorites', []);

        // using strtolower to make sure Paris and paris are treated as the same thing
        $isMain = (strtolower($city) === strtolower($mainCity));
        $isFavorite = in_array(strtolower($city), array_map('strtolower', $favorites));

        return view('city.show', [
            'city' => $current['name'],
            'current' => $current,
            'daily' => $dailyForecast,
            'isMain' => $isMain,
            'isFavorite' => $isFavorite
        ]);
    }

    // SET AS MAIN CITY
    public function setMain(Request $request)
    {
        $newMain = $request->input('city');
        // source tells me if they clicked from the detail page or the favorite sidebar
        $source = $request->input('source');

        if (!$newMain) return redirect()->back();

        $currentMain = session('weather_main', 'Dijon');
        $favorites = session('weather_favorites', []);

        // LOGIC:
        // if they clicked a favorite city i want to SWAP them
        // the favorite becomes main and the old main goes into favorites
        if ($source === 'favorite_swap') {
            // remove the new main from the favorites list
            $favorites = array_diff($favorites, [$newMain]);

            // push the old main city into the favorites list so we dont lose it
            if ($currentMain !== $newMain) {
                array_push($favorites, $currentMain);
            }
        } else {
            // if they just set it from the search page
            // i just overwrite the main city and remove it from favorites if it was there
            $favorites = array_diff($favorites, [$newMain]);
        }

        // save the new state to the session
        session([
            'weather_main' => $newMain,
            // array_unique ensures we dont have duplicates just in case
            'weather_favorites' => array_unique($favorites)
        ]);

        return redirect()->route('dashboard');
    }

    // 4. ADD OR REMOVE FAVORITE
    // simple logic to toggle the city in the list
    public function toggleFavorite(Request $request)
    {
        $city = $request->input('city');
        $action = $request->input('action'); // 'add' or 'remove'

        $favorites = session('weather_favorites', []);

        if ($action === 'add') {
            // only add if its not already there
            if (!in_array($city, $favorites)) {
                $favorites[] = $city;
            }
        } else {
            // remove the city from the array
            $favorites = array_diff($favorites, [$city]);
        }

        // save back to session and re-index the array keys
        session(['weather_favorites' => array_values($favorites)]);

        return redirect()->back();
    }
}
