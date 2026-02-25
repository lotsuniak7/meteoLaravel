<?php

namespace App\Console\Commands;

use App\Mail\DailyForecastMail;
use App\Models\User;
use App\Services\OpenWeatherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Artisan command to send daily weather forecasts to users.
 * This handles the scheduled task requirement of the project.
 */
class SendDailyForecasts extends Command
{
    protected $signature = 'weather:send-forecasts';
    protected $description = 'Send daily forecast emails to users who have subscribed cities.';

    public function handle(OpenWeatherService $weather): int
    {
        $users = User::with([
            'cities' => fn($q) => $q->where('daily_report', true),
        ])->get();

        $sent = 0;

        foreach ($users as $user) {
            $subscribedCities = $user->cities;

            if ($subscribedCities->isEmpty()) {
                continue;
            }

            Mail::to($user->email)->send(
                new DailyForecastMail($user, $subscribedCities->all(), $weather)
            );

            $sent++;
            $this->line("  ✓ Sent to {$user->email} ({$subscribedCities->count()} cities)");
        }

        $this->info("Done. Emails sent: {$sent}");

        return self::SUCCESS;
    }
}
