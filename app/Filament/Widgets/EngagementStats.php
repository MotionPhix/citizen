<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Comment;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EngagementStats extends BaseWidget
{
  protected function getStats(): array
  {
    try {
      $totalUsers = User::count();
      $activeUsers = User::active()->count();
      $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();

      // Get user registrations trend
      $usersTrend = collect(range(6, 0))->map(function ($day) {
        return User::whereDate('created_at', Carbon::now()->subDays($day))
          ->count();
      })->toArray();

      return [
        Stat::make('Active Users', $activeUsers)
          ->description($newUsers . ' new this week')
          ->descriptionIcon('heroicon-m-users')
          ->chart($usersTrend)
          ->color('success'),

        Stat::make('User Engagement', number_format(Comment::count()))
          ->description('Total interactions')
          ->descriptionIcon('heroicon-m-chat-bubble-left-right')
          ->color('info'),

        Stat::make('Verified Users', number_format(User::whereNotNull('email_verified_at')->count()))
          ->description('Email verified')
          ->descriptionIcon('heroicon-m-envelope')
          ->color('warning'),
      ];
    } catch (\Exception $e) {
      \Log::error('EngagementStats widget error: ' . $e->getMessage());

      return [
        Stat::make('Error', 'Data unavailable')
          ->description('Please check logs')
          ->descriptionIcon('heroicon-m-exclamation-triangle')
          ->color('danger'),
      ];
    }
  }
}
