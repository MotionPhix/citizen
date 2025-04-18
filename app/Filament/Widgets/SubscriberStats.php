<?php

namespace App\Filament\Widgets;

use App\Models\Subscriber;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubscriberStats extends BaseWidget
{
  protected function getStats(): array
  {
    $totalSubscribers = Subscriber::count();
    $activeSubscribers = Subscriber::active()->count();
    $unsubscribedCount = Subscriber::where('status', 'unsubscribed')->count();

    // Get new subscribers in the last 7 days
    $lastWeekSubscribers = Subscriber::where('created_at', '>=', Carbon::now()->subDays(7))
      ->count();

    // Get subscribers growth trend for the last 7 days
    $subscriberTrend = collect(range(6, 0))->map(function ($day) {
      return Subscriber::whereDate('created_at', Carbon::now()->subDays($day))
        ->count();
    })->toArray();

    // Calculate unsubscribe rate
    $unsubscribeRate = $totalSubscribers > 0
      ? round(($unsubscribedCount / $totalSubscribers) * 100, 1)
      : 0;

    return [
      Stat::make('Total Subscribers', $activeSubscribers)
        ->description($lastWeekSubscribers . ' new this week')
        ->descriptionIcon('heroicon-m-arrow-trending-up')
        ->chart($subscriberTrend)
        ->color('success'),

      Stat::make('Unsubscribe Rate', $unsubscribeRate . '%')
        ->description($unsubscribedCount . ' total unsubscribed')
        ->descriptionIcon('heroicon-m-arrow-trending-down')
        ->color($unsubscribeRate > 5 ? 'danger' : 'success'),

      Stat::make('Subscriber Retention', (100 - $unsubscribeRate) . '%')
        ->description('Overall retention rate')
        ->descriptionIcon('heroicon-m-users')
        ->color('info'),
    ];
  }
}
