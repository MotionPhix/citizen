<?php

namespace App\Filament\Resources\ImpactMetricResource\Pages;

use App\Filament\Resources\ImpactMetricResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Alignment;

class EditImpactMetric extends EditRecord
{
  protected static string $resource = ImpactMetricResource::class;
  public static string|\Filament\Support\Enums\Alignment $formActionsAlignment = Alignment::Right;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }
}
