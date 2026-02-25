<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for the City model
 * This class is responsible for transforming our Eloquent City models into
 * a structured JSON array for our API responses, ensuring we only expose
 * the necessary data to the client
 */
class CityResource extends JsonResource
{
    // transform the resource into an array.
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'is_favorite'  => $this->is_favorite,
            'daily_report' => $this->daily_report,
            'created_at'   => $this->created_at?->toISOString(),
        ];
    }
}
