<?php

namespace App\Filament\Resources\NewsletterIssueResource\Pages;

use App\Filament\Resources\NewsletterIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewsletterIssue extends EditRecord
{
  protected static string $resource = NewsletterIssueResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }
}
