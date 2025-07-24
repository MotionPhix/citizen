<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSubmissionResource\Pages;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\VerticalAlignment;
use App\Models\ContactSubmission;
use App\Notifications\ContactFormResponse;
use Filament\Forms;
use Filament\Notifications\Notification;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactSubmissionResource extends Resource implements HasShieldPermissions
{
  protected static ?string $model = ContactSubmission::class;
  protected static ?string $navigationIcon = 'heroicon-o-inbox';
  protected static ?string $navigationGroup = 'Communications';
  protected static ?string $navigationLabel = 'Contact Messages';
  protected static ?string $breadcrumb = 'Contact Messages';
  protected static ?int $navigationSort = 1;

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->weight('bold')
          ->label('From')
          ->description(fn($record) => $record->email)
          ->searchable(['name', 'email']),

        Tables\Columns\TextColumn::make('subject')
          ->label('Message')
          ->searchable([
            'subject',
            'message',
          ])
          ->color('primary')
          ->description(fn($record) => str($record->message)->limit(20))
          ->verticalAlignment(VerticalAlignment::Start)
          ->limit(40),

        Tables\Columns\TextColumn::make('status')
          ->badge()
          ->verticalAlignment(VerticalAlignment::Start)
          ->formatStateUsing(fn($state) => str($state)->title())
          ->colors([
            'danger' => ContactSubmission::STATUS_UNREAD,
            'warning' => ContactSubmission::STATUS_READ,
            'success' => [ContactSubmission::STATUS_RESPONDED, 'replied'],
            'gray' => ContactSubmission::STATUS_SPAM,
            'info' => ContactSubmission::STATUS_ARCHIVED,
          ]),

        Tables\Columns\TextColumn::make('spam_score')
          ->label('Spam %')
          ->numeric(1)
          ->suffix('%')
          ->color(fn($state) => $state > 70 ? 'danger' : ($state > 40 ? 'warning' : 'success'))
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),

        Tables\Columns\TextColumn::make('created_at')
          ->label('Received')
          ->dateTime('M j, Y g:i A')
          ->sortable()
          ->since()
          ->description(fn($record) => $record->created_at->format('M j, Y g:i A')),

        Tables\Columns\TextColumn::make('responded_at')
          ->label('Responded')
          ->dateTime('M j, Y g:i A')
          ->sortable()
          ->placeholder('Not responded')
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        Tables\Filters\SelectFilter::make('status')
          ->options([
            ContactSubmission::STATUS_UNREAD => 'Unread',
            ContactSubmission::STATUS_READ => 'Read',
            ContactSubmission::STATUS_RESPONDED => 'Responded',
            'replied' => 'Replied', // Legacy support
            ContactSubmission::STATUS_SPAM => 'Spam',
            ContactSubmission::STATUS_ARCHIVED => 'Archived',
          ])
          ->multiple(),

        Tables\Filters\Filter::make('likely_spam')
          ->label('Likely Spam')
          ->query(fn(Builder $query): Builder => $query->where('spam_score', '>=', 70))
          ->toggle(),

        Tables\Filters\Filter::make('recent')
          ->label('Last 7 Days')
          ->query(fn(Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(7)))
          ->toggle(),

        Tables\Filters\Filter::make('needs_response')
          ->label('Needs Response')
          ->query(fn(Builder $query): Builder => $query->whereIn('status', [
            ContactSubmission::STATUS_UNREAD,
            ContactSubmission::STATUS_READ
          ]))
          ->toggle(),
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\ViewAction::make()
            ->label('View Message')
            ->after(function (ContactSubmission $record) {
              // Auto mark as read when viewing from table
              if ($record->status === ContactSubmission::STATUS_UNREAD) {
                $record->markAsRead();
              }
            }),

          Tables\Actions\Action::make('mark_read')
            ->label('Mark as Read')
            ->icon('heroicon-o-eye')
            ->color('success')
            ->action(function (ContactSubmission $record): void {
              $record->markAsRead();
              Notification::make()
                ->success()
                ->title('Marked as read')
                ->send();
            })
            ->visible(fn(ContactSubmission $record) => $record->status === ContactSubmission::STATUS_UNREAD),

          Tables\Actions\Action::make('mark_spam')
            ->label('Mark as Spam')
            ->icon('heroicon-o-shield-exclamation')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (ContactSubmission $record): void {
              $record->markAsSpam();
              Notification::make()
                ->success()
                ->title('Marked as spam')
                ->send();
            })
            ->visible(fn(ContactSubmission $record) => $record->status !== ContactSubmission::STATUS_SPAM),

          Tables\Actions\Action::make('archive')
            ->label('Archive')
            ->icon('heroicon-o-archive-box')
            ->color('gray')
            ->requiresConfirmation()
            ->action(function (ContactSubmission $record): void {
              $record->archive();
              Notification::make()
                ->success()
                ->title('Message archived')
                ->send();
            })
            ->visible(fn(ContactSubmission $record) => $record->status !== ContactSubmission::STATUS_ARCHIVED),

          Tables\Actions\Action::make('reply')
            ->label('Quick Reply')
            ->form([
              Forms\Components\Textarea::make('response')
                ->label('Your Response')
                ->required()
                ->rows(6)
                ->maxLength(65535),
            ])
            ->modalWidth(MaxWidth::Large)
            ->action(function (ContactSubmission $record, array $data): void {
              $record->update([
                'response' => $data['response'],
                'status' => ContactSubmission::STATUS_RESPONDED,
                'responded_at' => now(),
                'responded_by' => auth()->id(),
              ]);

              // Send email to the contact
              $record->notify(new ContactFormResponse($record, $data['response']));

              Notification::make()
                ->title('Response sent successfully')
                ->success()
                ->send();
            })
            ->visible(fn(ContactSubmission $record) => !in_array($record->status, [ContactSubmission::STATUS_RESPONDED, 'replied']))
            ->icon('heroicon-o-paper-airplane')
            ->color('success'),

          Tables\Actions\DeleteAction::make()
            ->requiresConfirmation(),
        ])
          ->icon('heroicon-o-ellipsis-horizontal')
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListContactSubmissions::route('/'),
      'create' => Pages\CreateContactSubmission::route('/create'),
      'view' => Pages\ViewContactSubmission::route('/{record}'),
    ];
  }

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::where('status', 'unread')->count() ?: null;
  }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
        ];
    }
}
