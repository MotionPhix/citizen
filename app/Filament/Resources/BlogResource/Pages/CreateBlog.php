<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\MaxWidth;

class CreateBlog extends CreateRecord
{
  protected static string $resource = BlogResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $data['user_id'] = auth()->id();

    return $data;
  }

  public function getMaxContentWidth(): MaxWidth
  {
    return MaxWidth::FiveExtraLarge;
  }
}
