<?php

namespace App\Filament\Resources\ImpactMetricResource\Pages;

use App\Filament\Resources\ImpactMetricResource;
use App\Filament\Resources\ImpactMetricResource\Widgets\ImpactMetricsStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListImpactMetrics extends ListRecords
{
  protected static string $resource = ImpactMetricResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make()
        ->label('Add')
        ->icon('heroicon-o-plus')
        ->color('primary')
        ->successNotificationTitle('Metric created successfully'),
    ];
  }

  protected function getHeaderWidgets(): array
  {
    return [
      ImpactMetricsStatsWidget::class,
    ];
  }

  public function getMaxContentWidth(): MaxWidth
  {
    return MaxWidth::FourExtraLarge;
  }
}
