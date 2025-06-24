<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectOverviewStats extends BaseWidget
{
  protected function getStats(): array
  {
    $totalProjects = Project::count();
    $activeProjects = Project::where('status', 'current')->count();
    $completedProjects = Project::where('status', 'completed')->count();
    $totalBudget = Project::sum('budget');
    $peopleReached = Project::sum('people_reached');

    return [
      Stat::make('Active Projects', $activeProjects)
        ->description($totalProjects . ' total projects')
        ->descriptionIcon('heroicon-m-briefcase')
        ->color('success')
        ->chart([
          $activeProjects,
          $completedProjects,
        ]),

      Stat::make('Total Budget', '$ ' . number_format($totalBudget))
        ->description('Across all projects')
        ->descriptionIcon('heroicon-m-banknotes')
        ->color('warning'),

      Stat::make('People Reached', number_format($peopleReached))
        ->description('Direct beneficiaries')
        ->descriptionIcon('heroicon-m-users')
        ->color('info'),
    ];
  }
}
