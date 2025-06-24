<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BlogOverviewStats;
use App\Filament\Widgets\EngagementStats;
use App\Filament\Widgets\NewsletterIssueStats;
use App\Filament\Widgets\ProjectOverviewStats;
use App\Filament\Widgets\SubscriberStats;

class Dashboard extends \Filament\Pages\Dashboard
{
  protected static ?string $navigationIcon = '';

  protected function getHeaderWidgets(): array
  {
    return [
      ProjectOverviewStats::class,
    ];
  }

  public function getWidgets(): array
  {
    return [
      BlogOverviewStats::class,
      EngagementStats::class,
      SubscriberStats::class,
      NewsletterIssueStats::class,
    ];
  }

  public function getMaxContentWidth(): \Filament\Support\Enums\MaxWidth
  {
    return \Filament\Support\Enums\MaxWidth::FourExtraLarge;
  }

}
