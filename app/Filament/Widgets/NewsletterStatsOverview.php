<?php

namespace App\Filament\Widgets;

use App\Models\Newsletter;
use App\Models\Subscriber;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewsletterStatsOverview extends BaseWidget
{
  protected function getStats(): array
  {
    $totalSubscribers = Subscriber::active()->count();
    $totalNewsletters = Newsletter::count();
    $scheduledNewsletters = Newsletter::scheduled()->count();
    $sentNewsletters = Newsletter::sent()->count();

    return [
      Stat::make('Total Subscribers', $totalSubscribers)
        ->description('Active subscribers')
        ->descriptionIcon('heroicon-m-users')
        ->color('success'),

      Stat::make('Total Newsletters', $totalNewsletters)
        ->description($sentNewsletters . ' sent, ' . $scheduledNewsletters . ' scheduled')
        ->descriptionIcon('heroicon-m-envelope')
        ->color('primary'),

      Stat::make('Average Open Rate', '45%')
        ->description('Last 30 days')
        ->descriptionIcon('heroicon-m-chart-bar')
        ->chart([30, 55, 45, 35, 45, 55, 45]),
    ];
  }
}
