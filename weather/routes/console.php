<?php

use Illuminate\Support\Facades\Schedule;

// Send daily forecast emails every day at 06:00
Schedule::command('weather:send-forecasts')->dailyAt('06:00');
