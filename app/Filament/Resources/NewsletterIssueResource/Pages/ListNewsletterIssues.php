<?php

namespace App\Filament\Resources\NewsletterIssueResource\Pages;

use App\Filament\Resources\NewsletterIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsletterIssues extends ListRecords
{
  protected static string $resource = NewsletterIssueResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }
}
