<?php

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Filament\Resources\ContactSubmissionResource;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\MaxWidth;

class ViewContactSubmission extends ViewRecord
{
  protected static string $resource = ContactSubmissionResource::class;

  public function mount(int|string $record): void
  {
    parent::mount($record);

    // Mark as read when viewing if it's currently unread
    if ($this->record->status === 'unread') {
      $this->record->markAsRead();
    }
  }

  public function infolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->schema([
        Infolists\Components\Section::make()
          ->schema([
            Infolists\Components\TextEntry::make('name')
              ->label('From')
              ->icon('heroicon-o-user'),

            Infolists\Components\TextEntry::make('email')
              ->icon('heroicon-o-envelope')
              ->copyable(),

            Infolists\Components\TextEntry::make('subject')
              ->columnSpanFull()
              ->icon('heroicon-o-chat-bubble-left-ellipsis'),

            // The message with larger container
            Infolists\Components\TextEntry::make('message')
              ->columnSpanFull()
              ->prose()
              ->extraAttributes([
                'style' => 'min-height: 200px; max-height: 600px; overflow-y: auto;',
                'class' => 'prose-lg p-3 bg-gray-50 dark:bg-gray-800 rounded-lg'
              ])
              ->markdown(),

            Infolists\Components\TextEntry::make('status')
              ->badge()
              ->formatStateUsing(fn($state) => str($state)->title())
              ->color(fn(string $state): string => match ($state) {
                'unread' => 'danger',
                'read' => 'warning',
                'replied' => 'success',
                default => 'gray',
              }),
          ])
          ->columns(2),

        // Response Section (if there's a response)
        Infolists\Components\Section::make('Response')
          ->schema([
            Infolists\Components\TextEntry::make('response')
              ->columnSpanFull()
              ->prose()
              ->extraAttributes([
                'class' => 'prose p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800'
              ])
              ->markdown(),

            Infolists\Components\TextEntry::make('responded_at')
              ->dateTime()
              ->icon('heroicon-o-clock'),

            Infolists\Components\TextEntry::make('responder.name')
              ->label('Responded by')
              ->icon('heroicon-o-user')
              ->visible(fn($record) => $record->responder),

            Infolists\Components\TextEntry::make('response_time')
              ->label('Response Time')
              ->icon('heroicon-o-clock')
              ->visible(fn($record) => $record->responded_at),
          ])
          ->columns(3)
          ->visible(fn($record) => !empty($record->response)),

        // Submission Details Section
        Infolists\Components\Section::make('Submission Details')
          ->schema([
            Infolists\Components\TextEntry::make('submitted_at')
              ->dateTime()
              ->icon('heroicon-o-calendar'),

            Infolists\Components\TextEntry::make('created_at')
              ->label('Received at')
              ->dateTime()
              ->icon('heroicon-o-inbox'),

            Infolists\Components\TextEntry::make('time_since')
              ->label('Time ago')
              ->icon('heroicon-o-clock'),

            Infolists\Components\TextEntry::make('ip_address')
              ->icon('heroicon-o-globe-alt')
              ->copyable(),

            Infolists\Components\TextEntry::make('spam_score')
              ->numeric(2)
              ->suffix('%')
              ->color(fn($state) => $state > 70 ? 'danger' : ($state > 40 ? 'warning' : 'success'))
              ->icon('heroicon-o-shield-exclamation'),

            Infolists\Components\TextEntry::make('is_likely_spam')
              ->label('Likely Spam')
              ->badge()
              ->color(fn($state) => $state ? 'danger' : 'success')
              ->formatStateUsing(fn($state) => $state ? 'Yes' : 'No'),

            Infolists\Components\TextEntry::make('referrer')
              ->label('Referrer URL')
              ->openUrlInNewTab()
              ->visible(fn($record) => !empty($record->referrer))
              ->columnSpanFull(),

            Infolists\Components\TextEntry::make('user_agent')
              ->label('Browser/Device')
              ->columnSpanFull()
              ->extraAttributes([
                'class' => 'text-xs text-gray-600 dark:text-gray-400 font-mono'
              ]),
          ])
          ->columns(3)
          ->collapsible(),

        // Metadata Section (if exists)
        Infolists\Components\Section::make('Additional Metadata')
          ->schema([
            Infolists\Components\TextEntry::make('metadata')
              ->columnSpanFull()
              ->formatStateUsing(function ($state) {
                if (empty($state) || !is_array($state)) {
                  return 'No additional metadata';
                }
                return collect($state)->map(function ($value, $key) {
                  return "<strong>{$key}:</strong> " . (is_array($value) ? json_encode($value) : $value);
                })->implode('<br>');
              })
              ->html(),
          ])
          ->visible(fn($record) => !empty($record->metadata))
          ->collapsible(),
      ]);
  }

  protected function getActions(): array
  {
    return [
      ActionGroup::make([
        // Mark as Read/Unread Action
        \Filament\Actions\Action::make('toggleReadStatus')
          ->label(fn() => $this->record->status === 'unread' ? 'Mark as Read' : 'Mark as Unread')
          ->icon(fn() => $this->record->status === 'unread' ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
          ->color(fn() => $this->record->status === 'unread' ? 'success' : 'warning')
          ->action(function (): void {
            if ($this->record->status === 'unread') {
              $this->record->markAsRead();
            } else {
              $this->record->update(['status' => 'unread']);
            }

            \Filament\Notifications\Notification::make()
              ->success()
              ->title('Status updated successfully')
              ->send();

            $this->refreshFormData(['status']);
          })
          ->visible(fn() => in_array($this->record->status, ['unread', 'read'])),

        // Mark as Spam Action
        \Filament\Actions\Action::make('markAsSpam')
          ->label('Mark as Spam')
          ->icon('heroicon-o-shield-exclamation')
          ->color('danger')
          ->requiresConfirmation()
          ->action(function (): void {
            $this->record->markAsSpam();

            \Filament\Notifications\Notification::make()
              ->success()
              ->title('Marked as spam')
              ->send();

            $this->refreshFormData(['status']);
          })
          ->visible(fn() => $this->record->status !== 'spam'),

        // Archive Action
        \Filament\Actions\Action::make('archive')
          ->label('Archive')
          ->icon('heroicon-o-archive-box')
          ->color('gray')
          ->requiresConfirmation()
          ->action(function (): void {
            $this->record->archive();

            \Filament\Notifications\Notification::make()
              ->success()
              ->title('Message archived')
              ->send();

            $this->refreshFormData(['status']);
          })
          ->visible(fn() => $this->record->status !== 'archived'),

        // Reply Action
        \Filament\Actions\Action::make('reply')
          ->form([
            \Filament\Forms\Components\Textarea::make('response')
              ->label('Your Response')
              ->required()
              ->rows(8)
              ->maxLength(65535),
          ])
          ->modalIcon('heroicon-o-envelope')
          ->modalWidth(MaxWidth::ExtraLarge)
          ->action(function (array $data): void {
            $this->record->update([
              'response' => $data['response'],
              'status' => 'responded',
              'responded_at' => now(),
              'responded_by' => auth()->id(),
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

            $this->refreshFormData(['response', 'status', 'responded_at', 'responded_by']);
          })
          ->visible(fn() => !in_array($this->record->status, ['responded', 'replied']))
          ->icon('heroicon-o-paper-airplane')
          ->color('success'),
      ])->dropdownPlacement('bottom-end')
        ->icon('heroicon-o-ellipsis-horizontal')
    ];
  }

  public function getTitle(): string
  {
    $statusBadge = match ($this->record->status) {
      'unread' => '🔴',
      'read' => '🟡',
      'responded', 'replied' => '✅',
      'spam' => '🚫',
      'archived' => '📦',
      default => '📧'
    };

    return $statusBadge . ' ' . $this->record->subject . ' from ' . $this->record->name;
  }

  public function getMaxContentWidth(): MaxWidth|string|null
  {
    return MaxWidth::FourExtraLarge;
  }
}
