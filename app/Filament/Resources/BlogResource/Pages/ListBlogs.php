<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListBlogs extends ListRecords
{
  protected static string $resource = BlogResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }

  public function getMaxContentWidth(): \Filament\Support\Enums\MaxWidth
  {
    return \Filament\Support\Enums\MaxWidth::FourExtraLarge;
  }
}
