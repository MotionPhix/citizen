<?php

namespace App\Filament\Resources\NewsletterResource\Pages;

use App\Filament\Resources\NewsletterResource;
use App\Filament\Widgets\NewsletterStatsOverview;
use App\Jobs\SendNewsletter;
use App\Models\Subscriber;
use App\Notifications\NewsletterMail;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms;
use Filament\Notifications\Notification;

class ViewNewsletter extends ViewRecord
{
  protected static string $resource = NewsletterResource::class;

  public function infolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->schema([
        Infolists\Components\Section::make('Newsletter Details')
          ->schema([
            Infolists\Components\TextEntry::make('title')
              ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

            Infolists\Components\TextEntry::make('status')
              ->badge()
              ->color(fn (string $state): string => match ($state) {
                'draft' => 'gray',
                'scheduled' => 'warning',
                'sending' => 'info',
                'sent' => 'success',
                'failed' => 'danger',
                default => 'gray',
              }),

            Infolists\Components\TextEntry::make('scheduled_for')
              ->dateTime()
              ->visible(fn ($record) => $record->scheduled_for !== null),

            Infolists\Components\TextEntry::make('sent_at')
              ->dateTime()
              ->visible(fn ($record) => $record->sent_at !== null),

            Infolists\Components\TextEntry::make('sentBy.name')
              ->label('Sent By')
              ->visible(fn ($record) => $record->sent_at !== null),
          ])
          ->columns(2),

        Infolists\Components\Section::make('Content')
          ->schema([
            Infolists\Components\TextEntry::make('content')
              ->html()
              ->columnSpanFull(),
          ]),

        Infolists\Components\Section::make('Send Statistics')
          ->schema([
            Infolists\Components\TextEntry::make('subscribers_count')
              ->label('Total Recipients')
              ->state(fn ($record) => $record->subscribers()->count()),

            Infolists\Components\TextEntry::make('successful_sends')
              ->label('Successfully Sent')
              ->state(fn ($record) => $record->subscribers()->wherePivot('status', 'sent')->count()),

            Infolists\Components\TextEntry::make('failed_sends')
              ->label('Failed Sends')
              ->state(fn ($record) => $record->subscribers()->wherePivot('status', 'failed')->count()),

            Infolists\Components\TextEntry::make('pending_sends')
              ->label('Pending Sends')
              ->state(fn ($record) => $record->subscribers()->wherePivot('status', 'pending')->count()),
          ])
          ->columns(4)
          ->visible(fn ($record) => in_array($record->status, ['sending', 'sent'])),
      ]);
  }

  protected function getHeaderActions(): array
  {
    return [
      Actions\Action::make('sendTest')
        ->label('Send Test Email')
        ->form([
          Forms\Components\TextInput::make('email')
            ->label('Test Email Address')
            ->email()
            ->required(),
        ])
        ->action(function (array $data): void {
          $subscriber = new Subscriber([
            'email' => $data['email'],
            'name' => 'Test User',
          ]);

          try {
            $subscriber->notify(new NewsletterMail($this->record));

            Notification::make()
              ->success()
              ->title('Test email sent successfully')
              ->send();
          } catch (\Exception $e) {
            Notification::make()
              ->danger()
              ->title('Failed to send test email')
              ->body($e->getMessage())
              ->send();
          }
        })
        ->icon('heroicon-o-paper-airplane')
        ->visible(fn () => $this->record->status !== 'sent'),

      Actions\Action::make('send')
        ->label('Send Newsletter')
        ->requiresConfirmation()
        ->action(function (): void {
          if ($this->record->status === 'sent') {
            return;
          }

          SendNewsletter::dispatch($this->record);

          Notification::make()
            ->success()
            ->title('Newsletter queued for sending')
            ->send();
        })
        ->visible(fn () => $this->record->status === 'draft'),

      Actions\Action::make('duplicate')
        ->label('Duplicate Newsletter')
        ->icon('heroicon-o-document-duplicate')
        ->action(function (): void {
          $clone = $this->record->replicate();
          $clone->title = "Copy of " . $clone->title;
          $clone->status = 'draft';
          $clone->scheduled_for = null;
          $clone->sent_at = null;
          $clone->save();

          Notification::make()
            ->success()
            ->title('Newsletter duplicated')
            ->send();

          $this->redirect(self::getResource()::getUrl('edit', ['record' => $clone]));
        }),

      Actions\EditAction::make()
        ->visible(fn () => $this->record->status !== 'sent'),

      Actions\DeleteAction::make()
        ->visible(fn () => $this->record->status !== 'sent'),
    ];
  }

  protected function getFooterWidgets(): array
  {
    return [
      NewsletterStatsOverview::class,
    ];
  }
}
