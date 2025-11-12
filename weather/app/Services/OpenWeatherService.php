<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OpenWeatherService
{
    protected $base;
    protected $key;

    public function __construct()
    {
        $this->base = config('services.openweather.base', 'https://api.openweathermap.org');
        $this->key = config('services.openweather.key');
    }

    public function current(string $place): array
    {
        // Cache for 5 minutes
        return Cache::remember("ow:current:{$place}", 300, function () use ($place) {
            $res = Http::retry(2, 100)->get("{$this->base}/data/2.5/weather", [
                'q'     => $place,
                'appid' => $this->key,
                'units' => 'metric',
                'lang'  => 'en',
            ]);

            // Basic error handling | After I will change
            if ($res->failed()) {
                return ['error' => $res->json('message') ?? 'fetch_failed'];
            }

            return $res->json();
        });
    }

    public function forecast(float $lat, float $lon): array
    {
        // Create a unique cache key
        $cacheKey = "ow:forecast:{$lat}:{$lon}";

        return Cache::remember($cacheKey, 300, function () use ($lat, $lon) {
            $res = Http::retry(2, 100)->get("{$this->base}/data/2.5/forecast", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->key,
                'units' => 'metric',
                'lang'  => 'en',
            ]);

            // Basic error handling | After I will change
            if ($res->failed()) {
                return ['error' => $res->json('message') ?? 'fetch_failed'];
            }

            return $res->json();
        });
    }
}
