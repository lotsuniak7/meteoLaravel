<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Thanks to this file, Laravel knows how to read/write data to the cities table
class City extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'is_favorite',
        'daily_report',
    ];

    protected function casts(): array
    {
        return [
            'is_favorite'  => 'boolean',
            'daily_report' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
