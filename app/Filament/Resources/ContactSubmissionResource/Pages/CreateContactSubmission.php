<?php

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Filament\Resources\ContactSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\MaxWidth;

class CreateContactSubmission extends CreateRecord
{
  protected static string $resource = ContactSubmissionResource::class;

  public function getMaxContentWidth(): MaxWidth|string|null
  {
    return MaxWidth::FourExtraLarge;
  }
}
