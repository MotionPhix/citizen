<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('newsletters:send-scheduled')->everyMinute();

Schedule::command('newsletter:send-biweekly')->twiceMonthly(1, 15, '13:00');
