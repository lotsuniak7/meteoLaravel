<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForecastResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'city'  => $this->resource['place'],
            'daily' => collect($this->resource['daily'])->map(fn ($day) => [
                'date'        => $day['date'],
                'temp_min'    => round($day['temp_min'], 1),
                'temp_max'    => round($day['temp_max'], 1),
                'description' => $day['description'],
                'icon'        => $day['icon'],
            ])->values(),
        ];
    }
}
