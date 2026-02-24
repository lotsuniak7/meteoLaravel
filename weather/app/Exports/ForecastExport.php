<?php

namespace App\Exports;

use App\Services\OpenWeatherService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ForecastExport implements FromCollection, WithHeadings, WithStyles
{
    public function __construct(
        private readonly string             $city,
        private readonly OpenWeatherService $weather
    )
    {
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $rows = $this->weather->forecastRows($this->city);

        return collect($rows)->map(fn($row) => [
            $row['date'],
            $row['temp_min'],
            $row['temp_max'],
            $row['description'],
        ]);
    }

    public function headings(): array
    {
        return ['Date', 'Temp Min (°C)', 'Temp Max (°C)', 'Condition'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
