<?php

namespace App\Console\Commands;

use App\Services\OpenWeatherService;
use Illuminate\Console\Command;

/**
 * Artisan command to display the current weather for a specific city directly in the terminal.
 * This fulfills the requirement to have a CLI tool for fetching weather.
 */
class ShowCityWeather extends Command
{
    protected $signature   = 'weather:current {city : The city name to look up}';
    protected $description = 'Display the current weather for a given city.';

    public function handle(OpenWeatherService $weather): int
    {
        $city = $this->argument('city');
        $data = $weather->current($city);

        // if the API returns an error we display an error message
        if (isset($data['error'])) {
            $this->error("City not found: {$city}");
            return self::FAILURE;
        }

        $this->newLine();
        $this->line("  <fg=cyan;options=bold>📍 {$data['name']}, {$data['sys']['country']}</>");
        $this->newLine();

        $this->table(
            ['Property', 'Value'],
            [
                ['Temperature',  round($data['main']['temp'], 1) . ' °C'],
                ['Feels like',   round($data['main']['feels_like'], 1) . ' °C'],
                ['Min / Max',    round($data['main']['temp_min']) . '° / ' . round($data['main']['temp_max']) . '°'],
                ['Humidity',     $data['main']['humidity'] . '%'],
                ['Pressure',     $data['main']['pressure'] . ' hPa'],
                ['Wind speed',   $data['wind']['speed'] . ' m/s'],
                ['Condition',    ucfirst($data['weather'][0]['description'])],
            ]
        );

        $this->newLine();

        return self::SUCCESS;
    }
}
