<?php

namespace App\Filament\Widgets;

use App\Models\NewsletterIssue;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewsletterIssueStats extends BaseWidget
{
  protected function getStats(): array
  {
    try {
      $totalIssues = NewsletterIssue::count();
      $draftIssues = NewsletterIssue::where('status', 'draft')->count();
      $scheduledIssues = NewsletterIssue::where('status', 'scheduled')->count();
      $sentIssues = NewsletterIssue::where('status', 'sent')->count(); // Fixed: changed from 'published' to 'sent'
      $sendingIssues = NewsletterIssue::where('status', 'sending')->count();

      return [
        Stat::make('Total Issues', $totalIssues)
          ->description('All newsletter issues')
          ->descriptionIcon('heroicon-m-envelope')
          ->color('primary'),

        Stat::make('Scheduled', $scheduledIssues)
          ->description('Pending publication')
          ->descriptionIcon('heroicon-m-clock')
          ->color('warning'),

        Stat::make('Sent', $sentIssues)
          ->description('Successfully delivered')
          ->descriptionIcon('heroicon-m-check-circle')
          ->color('success'),
      ];
    } catch (\Exception $e) {
      \Log::error('NewsletterIssueStats widget error: ' . $e->getMessage());

      return [
        Stat::make('Error', 'Data unavailable')
          ->description('Please check logs')
          ->descriptionIcon('heroicon-m-exclamation-triangle')
          ->color('danger'),
      ];
    }
  }
}
