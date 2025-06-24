<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
  protected static string $resource = UserResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $data['user_id'] = auth()->id();

    return $data;
  }

  public function getMaxContentWidth(): \Filament\Support\Enums\MaxWidth
  {
    return \Filament\Support\Enums\MaxWidth::FourExtraLarge;
  }
}
