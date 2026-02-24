<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'city'        => $this->resource['name'],
            'country'     => $this->resource['sys']['country'] ?? null,
            'temperature' => [
                'current'    => round($this->resource['main']['temp'], 1),
                'feels_like' => round($this->resource['main']['feels_like'], 1),
                'min'        => round($this->resource['main']['temp_min'], 1),
                'max'        => round($this->resource['main']['temp_max'], 1),
            ],
            'humidity'    => $this->resource['main']['humidity'],
            'pressure'    => $this->resource['main']['pressure'],
            'wind'        => [
                'speed'   => $this->resource['wind']['speed'],
                'degrees' => $this->resource['wind']['deg'] ?? null,
            ],
            'condition'   => $this->resource['weather'][0]['description'] ?? null,
            'icon'        => $this->resource['weather'][0]['icon'] ?? null,
            'coordinates' => $this->resource['coord'] ?? null,
        ];
    }
}
