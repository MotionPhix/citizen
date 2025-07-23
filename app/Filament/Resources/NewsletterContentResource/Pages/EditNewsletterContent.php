<?php

namespace App\Filament\Resources\NewsletterContentResource\Pages;

use App\Filament\Resources\NewsletterContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewsletterContent extends EditRecord
{
  protected static string $resource = NewsletterContentResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
      Actions\Action::make('duplicate')
        ->label('Duplicate')
        ->icon('heroicon-o-document-duplicate')
        ->action(function () {
          $newRecord = $this->record->replicate();
          $newRecord->title = $this->record->title . ' (Copy)';
          $newRecord->save();

          $this->redirect($this->getResource()::getUrl('edit', ['record' => $newRecord]));
        }),
    ];
  }

  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }

  protected function mutateFormDataBeforeSave(array $data): array
  {
    // Ensure metadata is properly formatted
    if (empty($data['metadata'])) {
      $data['metadata'] = [];
    }

    return $data;
  }
}
