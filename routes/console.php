<?php

use App\Models\ImpactMetric;
use Illuminate\Support\Facades\Schedule;

Schedule::command('newsletters:send-scheduled')->everyMinute();

Schedule::command('newsletter:send-biweekly')->twiceMonthly(1, 15, '13:00');

Schedule::command('metrics:populate-history')->dailyAt('01:00');

Schedule::call(function () {
  ImpactMetric::all()
    ->each(function ($metric) {
      $metric->recordHistory('Weekly snapshot');
    });
})->weekly()->sundays()->at('00:00');
