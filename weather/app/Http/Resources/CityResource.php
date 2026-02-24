<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
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
