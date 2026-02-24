<?php

namespace App\Mail;

use App\Exports\ForecastExport;
use App\Models\City;
use App\Models\User;
use App\Services\OpenWeatherService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class DailyForecastMail extends Mailable
{
    use Queueable, SerializesModels;

    // this will hold the forecast rows for each city
    // keyed by city name so i can loop through them in the blade template
    /** @var array<string, array> Keyed by city name */
    public array $forecasts = [];

    public function __construct(
        // the user who will receive the email
        public readonly User $recipient,
        // the list of cities they subscribed to
        /** @var City[] */
        public readonly array $cities,
        private readonly OpenWeatherService $weather
    )
    {
        // pre-fetching forecast data for each city in the constructor
        foreach ($cities as $city) {
            $rows = $this->weather->forecastRows($city->name);
            $this->forecasts[$city->name] = $rows;
        }
    }

    // this defines the subject line of the email
    // i add the current date so the user knows which day the forecast is for
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your daily weather forecast — ' . now()->format('d M Y'),
        );
    }

    // pointing to the blade template that renders the email body
    public function content(): Content
    {
        return new Content(
            view: 'mail.daily-forecast',
        );
    }

    // attaching one csv file per city so the user can open it in excel
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->cities as $city) {
            $export = new ForecastExport($city->name, $this->weather);
            $filename = 'forecast_' . strtolower(str_replace(' ', '_', $city->name)) . '.csv';

            // generating the csv on the fly and attaching it directly
            $attachments[] = Attachment::fromData(
                fn() => Excel::raw($export, \Maatwebsite\Excel\Excel::CSV),
                $filename
            )->withMime('text/csv');
        }

        return $attachments;
    }
}
