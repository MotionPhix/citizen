<?php

namespace App\Filament\Resources\ImpactMetricResource\Pages;

use App\Filament\Resources\ImpactMetricResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\MaxWidth;

class CreateImpactMetric extends CreateRecord
{
  protected static string $resource = ImpactMetricResource::class;

  public function getMaxContentWidth(): MaxWidth
  {
    return MaxWidth::FourExtraLarge;
  }
}
