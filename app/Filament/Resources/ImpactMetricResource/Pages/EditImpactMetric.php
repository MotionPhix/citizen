<?php

namespace App\Filament\Resources\ImpactMetricResource\Pages;

use App\Filament\Resources\ImpactMetricResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\MaxWidth;

class EditImpactMetric extends EditRecord
{
  protected static string $resource = ImpactMetricResource::class;

  public function getTitle(): \Illuminate\Contracts\Support\Htmlable|string
  {
    return "Edit {$this->record->title}";
  }

  public function getMaxContentWidth(): MaxWidth
  {
    return MaxWidth::FourExtraLarge;
  }

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make()
        ->name('Delete Metric')
        ->color('danger')
        ->icon('heroicon-o-trash')
        ->requiresConfirmation()
        ->successNotificationTitle('Metric deleted successfully')
    ];
  }
}
