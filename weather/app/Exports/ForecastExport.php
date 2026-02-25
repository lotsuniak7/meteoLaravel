<?php

namespace App\Exports;

use App\Services\OpenWeatherService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Handles the generation of the forecast Excel/CSV export
 * This class implements the required interfaces from the 'maatwebsite/excel' package
 * to format our forecast data into a downloadable spreadsheet
 */
class ForecastExport implements FromCollection, WithHeadings, WithStyles
{
    public function __construct(
        private readonly string $city,
        private readonly OpenWeatherService $weather
    )
    {
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $rows = $this->weather->forecastRows($this->city);

        // Convert the array to a Collection and map each day's forecast into a flat array structure
        // corresponding to the columns defined in the headings() method
        return collect($rows)->map(fn($row) => [
            $row['date'],
            $row['temp_min'],
            $row['temp_max'],
            $row['description'],
        ]);
    }

    // Define the column headers for the first row of the exported file
    public function headings(): array
    {
        return ['Date', 'Temp Min (°C)', 'Temp Max (°C)', 'Condition'];
    }

    // styles | font
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
