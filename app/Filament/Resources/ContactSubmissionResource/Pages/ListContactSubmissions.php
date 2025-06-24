<?php

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Filament\Resources\ContactSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListContactSubmissions extends ListRecords
{
  protected static string $resource = ContactSubmissionResource::class;
  protected static ?string $title = 'Messages';

  public function getMaxContentWidth(): MaxWidth|string|null
  {
    return MaxWidth::FourExtraLarge;
  }

  public function getBreadcrumb(): ?string
  {
    return 'List';
  }
}
