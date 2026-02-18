<?php

namespace App\Http\Controllers;

use App\Exports\ForecastExport;
use App\Models\City;
use App\Services\OpenWeatherService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class CityController extends Controller
{
    public function __construct(private readonly OpenWeatherService $weather) {}

    // List all cities saved by the user
    public function index(): View
    {
        $cities = Auth::user()->cities()->orderByDesc('is_favorite')->orderBy('name')->get();

        return view('cities.index', compact('cities'));
    }

    // SEARCH LOGIC
    // it takes the city name and redirects the user to the proper detail page url
    public function search(Request $request): RedirectResponse
    {
        //delete spaces
        $city = trim($request->input('city'));

        // if they pressed enter without typing anything
        if (empty($city)) {
            return redirect()->back()->with('error', 'Please enter a city name');
        }

        // redirecting to the show method below
        return redirect()->route('city.show', ['city' => $city]);
    }

    // DETAILED CITY PAGE
    // shows current weather and daily forecast for a specific city
    public function show($city, OpenWeatherService $weatherService): View|RedirectResponse
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
            $raw = $this->weather->forecast($current['coord']['lat'], $current['coord']['lon']);
            $dailyForecast = $this->weather->extractDailyForecast($raw);
        }

        $user = Auth::user();
        $savedCity  = $user->cities()->where('name', $current['name'])->first();

        return view('city.show', [
            'city'        => $current['name'],
            'current'     => $current,
            'daily'       => $dailyForecast,
            'isSaved'     => $savedCity !== null,
            'isFavorite'  => $savedCity?->is_favorite ?? false,
            'dailyReport' => $savedCity?->daily_report ?? false,
        ]);
    }

    // Add a city to the user's list
    public function add(Request $request): RedirectResponse
    {
        $cityName = $request->input('city');

        // Validate the city exists via API before saving
        $geo = $this->weather->geo($cityName);

        if (isset($geo['error'])) {
            return redirect()->back()->with('error', 'City not found.');
        }

        $canonicalName = $geo['name'];

        try {
            Auth::user()->cities()->firstOrCreate(
                ['name' => $canonicalName],
                ['is_favorite' => false, 'daily_report' => false]
            );
        } catch (\Exception) {
            // Unique constraint: city already exists for this user, that's fine
        }

        return redirect()->back()->with('success', "{$canonicalName} added to your cities.");
    }

    // Remove a city from the user's list
    public function remove(string $city): RedirectResponse
    {
        Auth::user()->cities()->where('name', $city)->delete();

        return redirect()->back()->with('success', "{$city} removed.");
    }

    // add or remove favorite
    // simple logic to toggle the city in the list
    public function toggleFavorite(Request $request, string $city)
    {
        $user = Auth::user();
        $cityModel = $user->cities()->where('name', $city)->first();

        if (!$cityModel) {
            return redirect()->back()->with('error', 'City not found in your list.');
        }

        if ($cityModel->is_favorite) {
            // Already favorite: unfavorite it
            $cityModel->update(['is_favorite' => false]);
        } else {
            // Remove favorite from any other city first (only one allowed)
            $user->cities()->where('is_favorite', true)->update(['is_favorite' => false]);
            $cityModel->update(['is_favorite' => true]);

            return redirect()->back();
        }
    }

    // Toggle daily report subscription
    public function toggleDailyReport(string $city): RedirectResponse
    {
        $cityModel = Auth::user()->cities()->where('name', $city)->first();

        if (!$cityModel) {
            return redirect()->back()->with('error', 'City not found in your list.');
        }

        $cityModel->update(['daily_report' => !$cityModel->daily_report]);

        return redirect()->back();
    }

    // Export forecast as XLSX
    public function exportXlsx(string $city): mixed
    {
        $filename = 'forecast_' . strtolower(str_replace(' ', '_', $city)) . '.xlsx';

        return Excel::download(new ForecastExport($city, $this->weather), $filename);
    }

    // Export forecast as CSV
    public function exportCsv(string $city): mixed
    {
        $filename = 'forecast_' . strtolower(str_replace(' ', '_', $city)) . '.csv';

        return Excel::download(new ForecastExport($city, $this->weather), $filename, \Maatwebsite\Excel\Excel::CSV);
    }
}
