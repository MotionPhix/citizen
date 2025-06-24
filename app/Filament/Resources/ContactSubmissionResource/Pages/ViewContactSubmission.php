<?php

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Filament\Resources\ContactSubmissionResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\MaxWidth;

class ViewContactSubmission extends ViewRecord
{
  protected static string $resource = ContactSubmissionResource::class;

  public function infolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->schema([
        Infolists\Components\Section::make()
          ->schema([
            Infolists\Components\TextEntry::make('name')
              ->label('From'),

            Infolists\Components\TextEntry::make('email'),

            Infolists\Components\TextEntry::make('subject')
              ->columnSpanFull(),

            // The message with larger container
            Infolists\Components\TextEntry::make('message')
              ->columnSpanFull()
              ->prose() // This adds better text formatting
              ->extraAttributes([
                'style' => 'min-height: 200px; max-height: 400px; overflow-y: auto;',
                'class' => 'prose-lg p-3 bg-gray-50 dark:bg-gray-800 rounded-lg'
              ])
              ->markdown(), // If you want to support markdown formatting

            Infolists\Components\TextEntry::make('status')
              ->badge()
              ->color(fn (string $state): string => match ($state) {
                'unread' => 'danger',
                'read' => 'warning',
                'replied' => 'success',
                default => 'gray',
              }),
          ])
          ->columns(2),

        // Additional section for submission metadata
        Infolists\Components\Section::make('Submission Details')
          ->schema([
            Infolists\Components\TextEntry::make('created_at')
              ->dateTime(),
            Infolists\Components\TextEntry::make('ip_address'),
            Infolists\Components\TextEntry::make('user_agent')
              ->columnSpanFull(),
          ])
          ->collapsible()
      ]);
  }

  protected function getHeaderActions(): array
  {
    return [
      \Filament\Actions\Action::make('reply')
        ->form([
          \Filament\Forms\Components\Textarea::make('response')
            ->label('Your Response')
            ->required()
            ->rows(8) // Make the response textarea larger too
            ->maxLength(65535),
        ])
        ->action(function (array $data): void {
          $this->record->update([
            'response' => $data['response'],
            'status' => 'replied',
          ]);

          // Send email to the contact
          $this->record->notify(new \App\Notifications\ContactFormResponse(
            $this->record,
            $data['response']
          ));

          \Filament\Notifications\Notification::make()
            ->success()
            ->title('Response sent successfully')
            ->send();
        })
        ->visible(fn () => $this->record->status !== 'replied')
        ->icon('heroicon-o-paper-airplane')
        ->color('success'),
    ];
  }

  public function getTitle(): string
  {
    return 'Viewing ' . $this->record->subject . ' from ' . $this->record->name;
  }

  public function getMaxContentWidth(): MaxWidth|string|null
  {
    return MaxWidth::FourExtraLarge;
  }
}
