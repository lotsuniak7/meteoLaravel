<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

// when user go to site, service active
class OpenWeatherService
{
    protected $base;
    protected $key;

    public function __construct()
    {
        $this->base = config('services.openweather.base', 'https://api.openweathermap.org');
        $this->key = config('services.openweather.key');
    }

    // ── Geo: resolve city name → canonical name + coordinates ─────
    public function geo(string $place, int $limit = 1): array
    {
        return Cache::remember("ow:geo:{$place}", 3600, function () use ($place, $limit) {
            $res = Http::retry(2, 100)->get("{$this->base}/geo/1.0/direct", [
                'q'     => $place,
                'limit' => $limit,
                'appid' => $this->key,
            ]);

            if ($res->failed() || empty($res->json())) {
                return ['error' => 'city_not_found'];
            }

            return $res->json()[0];
        });
    }

    // weather now for place
    public function current(string $place): array
    {

        // not nessesary but if we have many users who send we gonna be banned
        return Cache::remember("ow:current:{$place}", 300, function () use ($place) {
            // If api error or else happened, we try one more time
            $res = Http::retry(2, 100)->get("{$this->base}/data/2.5/weather", [
                'q'     => $place,
                'appid' => $this->key,
                'units' => 'metric',
                'lang'  => 'en',
            ]);

            if ($res->failed()) {
                return ['error' => $res->json('message') ?? 'fetch_failed'];
            }

            return $res->json();
        });
    }

    //-------------------------1-------------------------
    // get the weather for 5 days
    public function forecast(float $lat, float $lon): array
    {
        $cacheKey = "ow:forecast:{$lat}:{$lon}";

        //-------------------------2-------------------------
        return Cache::remember($cacheKey, 300, function () use ($lat, $lon) {
            $response = Http::retry(2, 100)->get("{$this->base}/data/2.5/forecast", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->key,
                'units' => 'metric',
                'lang' => 'en',
            ]);

            if ($response->failed()) {
                return ['error' => $response->json('message') ?? 'forecast_fetch_failed'];
            }

            return $response->json();
        });
    }

    // ── Forecast by city name, resolves via geo first ──────
    public function forecastByName(string $place): array
    {
        $current = $this->current($place);

        if (isset($current['error'])) {
            return $current;
        }

        return $this->forecast($current['coord']['lat'], $current['coord']['lon']);
    }


    // For hourly weather today and tomorrow
    public function extractHourlyForecast(array $forecast): array
    {
        // if api send nothing or broke
        if (!isset($forecast['list'])) {
            return [];
        }

        // actuel time
        $now = Carbon::now();
        // remember the date of today
        $today = $now->toDateString();
        // same, take today and add 1 day
        $tomorrow = $now->copy()->addDay()->toDateString();

        $result = [
            'today' => [],
            'tomorrow' => [],
        ];

        // for detail analyse of weather list
        foreach ($forecast['list'] as $item) {
            // Full time with date exactly
            $dateTime = Carbon::parse($item['dt_txt']);
            // only date
            $date = $dateTime->toDateString();

            //-------------------------4-------------------------
            // don't take the past days
            if ($dateTime->isPast()) {
                continue;
            }

            if ($date === $today) {
                $result['today'][] = $item;
            }

            if ($date === $tomorrow) {
                $result['tomorrow'][] = $item;
            }
        }

        return $result;
    }


    // daily forecast
    public function extractDailyForecast(array $forecast): array
    {
        // if api is broke or other
        if (!isset($forecast['list'])) {
            return [];
        }

        $daily = [];

        foreach ($forecast['list'] as $item) {
            // we take the date and modify, like  2023-10-05 15:00:00 just in "2023-10-05"
            $date = Carbon::parse($item['dt_txt'])->format('Y-m-d');

            // If we don't already create the list for this day, we gonna create
            if (!isset($daily[$date])) {
                $daily[$date] = [
                    'date' => Carbon::parse($item['dt_txt'])->format('l, d M'),
                    'temp_min' => $item['main']['temp'],
                    'temp_max' => $item['main']['temp'],
                    'icon' => $item['weather'][0]['icon'],
                    'description' => $item['weather'][0]['description'],
                ];
            } else {
                // here we compare the data, min max, if new is less or more, we take
                $daily[$date]['temp_min'] = min($daily[$date]['temp_min'], $item['main']['temp']);
                $daily[$date]['temp_max'] = max($daily[$date]['temp_max'], $item['main']['temp']);

                // usually the first data point for a day is at 00:00 = midnight
                // so the icon is usually a moon 'n'.
                // but i want to show the user what the weather looks like during the day.
                // so i check if the icon name has 'd' (day) in it.
                // if i find a day icon, i use that one instead of the moon.
                if (str_contains($item['weather'][0]['icon'], 'd')) {
                    $daily[$date]['icon'] = $item['weather'][0]['icon'];
                }
            }
        }

        return array_values($daily);
    }

    // ── flat rows for CSV/Excel export ───
    public function forecastRows(string $place): array
    {
        $raw    = $this->forecastByName($place);
        $daily  = $this->extractDailyForecast($raw);
        $rows   = [];

        foreach ($daily as $day) {
            $rows[] = [
                'date'        => $day['date'],
                'temp_min'    => round($day['temp_min'], 1),
                'temp_max'    => round($day['temp_max'], 1),
                'description' => $day['description'],
            ];
        }

        return $rows;
    }
}
