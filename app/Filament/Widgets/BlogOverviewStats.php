<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use App\Models\Comment;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogOverviewStats extends BaseWidget
{
  protected function getStats(): array
  {
    try {
      $totalPosts = Blog::count();
      $publishedPosts = Blog::where('is_published', true)->count();
      $totalViews = Blog::sum('view_count') ?? 0;
      $totalComments = Comment::count();

      // Get posts from last 7 days
      $recentPosts = Blog::where('created_at', '>=', Carbon::now()->subDays(7))->count();

      // Get daily views for the last 7 days
      $viewsTrend = collect(range(6, 0))->map(function ($day) {
        return Blog::whereDate('created_at', Carbon::now()->subDays($day))
          ->sum('view_count') ?? 0;
      })->toArray();

      return [
        Stat::make('Blog Posts', $publishedPosts)
          ->description($recentPosts . ' new this week')
          ->descriptionIcon('heroicon-m-document-text')
          ->chart($viewsTrend)
          ->color('primary'),

        Stat::make('Total Views', number_format($totalViews))
          ->description('Across all posts')
          ->descriptionIcon('heroicon-m-eye')
          ->color('success'),

        Stat::make('Comments', number_format($totalComments))
          ->description('User engagement')
          ->descriptionIcon('heroicon-m-chat-bubble-left-right')
          ->color('info'),
      ];
    } catch (\Exception $e) {
      \Log::error('BlogOverviewStats widget error: ' . $e->getMessage());

      return [
        Stat::make('Error', 'Data unavailable')
          ->description('Please check logs')
          ->descriptionIcon('heroicon-m-exclamation-triangle')
          ->color('danger'),
      ];
    }
  }
}
