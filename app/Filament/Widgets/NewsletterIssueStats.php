<?php

namespace App\Filament\Widgets;

use App\Models\NewsletterIssue;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewsletterIssueStats extends BaseWidget
{
  protected function getStats(): array
  {
    $totalIssues = NewsletterIssue::count();
    $draftIssues = NewsletterIssue::where('status', 'draft')->count();
    $scheduledIssues = NewsletterIssue::where('status', 'scheduled')->count();
    $publishedIssues = NewsletterIssue::where('status', 'published')->count();

    return [
      Stat::make('Total Issues', $totalIssues)
        ->description('All newsletter issues')
        ->descriptionIcon('heroicon-m-envelope')
        ->color('primary'),

      Stat::make('Scheduled', $scheduledIssues)
        ->description('Pending publication')
        ->descriptionIcon('heroicon-m-clock')
        ->color('warning'),

      Stat::make('Published', $publishedIssues)
        ->description('Successfully sent')
        ->descriptionIcon('heroicon-m-check-circle')
        ->color('success'),
    ];
  }
}
