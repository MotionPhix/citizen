<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriberResource\Pages;
use App\Models\Subscriber;
use Filament\Forms;
use Filament\Forms\Form;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;

class SubscriberResource extends Resource implements HasShieldPermissions
{
  protected static ?string $model = Subscriber::class;
  protected static ?string $navigationIcon = 'heroicon-o-user-group';
  protected static ?string $navigationGroup = 'Newsletter';
  protected static ?string $navigationLabel = 'Subscribers';
  protected static ?int $navigationSort = 3;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('email')
          ->email()
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('name')
          ->maxLength(255),
        Forms\Components\Select::make('status')
          ->options([
            'subscribed' => 'Subscribed',
            'unsubscribed' => 'Unsubscribed',
          ])
          ->required()
          ->default('subscribed'),
        Forms\Components\DateTimePicker::make('unsubscribed_at'),
        Forms\Components\KeyValue::make('preferences')
          ->keyLabel('Setting')
          ->valueLabel('Value')
          ->columnSpanFull(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('email')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('name')
          ->searchable()
          ->sortable(),
        Tables\Columns\BadgeColumn::make('status')
          ->colors([
            'success' => 'subscribed',
            'danger' => 'unsubscribed',
          ])
          ->sortable(),
        Tables\Columns\TextColumn::make('unsubscribed_at')
          ->label('Unsubscribed At')
          ->dateTime()
          ->sortable()
          ->placeholder('Still subscribed'),
        Tables\Columns\TextColumn::make('created_at')
          ->label('Subscribed At')
          ->dateTime()
          ->sortable(),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        Tables\Filters\SelectFilter::make('status')
          ->options([
            'subscribed' => 'Subscribed',
            'unsubscribed' => 'Unsubscribed',
          ]),
        Tables\Filters\Filter::make('recent_subscribers')
          ->label('Recent (Last 30 days)')
          ->query(fn(Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30)))
          ->toggle(),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('unsubscribe')
          ->label('Unsubscribe')
          ->icon('heroicon-o-x-mark')
          ->color('danger')
          ->action(function (Subscriber $record): void {
            $record->update([
              'status' => 'unsubscribed',
              'unsubscribed_at' => now(),
            ]);

            Notification::make()
              ->success()
              ->title('Subscriber unsubscribed')
              ->send();
          })
          ->requiresConfirmation()
          ->visible(fn(Subscriber $record): bool => $record->status === 'subscribed'),
        Tables\Actions\Action::make('resubscribe')
          ->label('Resubscribe')
          ->icon('heroicon-o-check')
          ->color('success')
          ->action(function (Subscriber $record): void {
            $record->update([
              'status' => 'subscribed',
              'unsubscribed_at' => null,
            ]);

            Notification::make()
              ->success()
              ->title('Subscriber resubscribed')
              ->send();
          })
          ->requiresConfirmation()
          ->visible(fn(Subscriber $record): bool => $record->status === 'unsubscribed'),
        Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
          Tables\Actions\BulkAction::make('bulk_unsubscribe')
            ->label('Unsubscribe Selected')
            ->icon('heroicon-o-x-mark')
            ->color('danger')
            ->action(function ($records): void {
              foreach ($records as $record) {
                $record->update([
                  'status' => 'unsubscribed',
                  'unsubscribed_at' => now(),
                ]);
              }

              Notification::make()
                ->success()
                ->title('Subscribers unsubscribed')
                ->send();
            })
            ->requiresConfirmation()
            ->deselectRecordsAfterCompletion(),

          Tables\Actions\BulkAction::make('bulk_resubscribe')
            ->label('Resubscribe Selected')
            ->icon('heroicon-o-check')
            ->color('success')
            ->action(function ($records): void {
              foreach ($records as $record) {
                $record->update([
                  'status' => 'subscribed',
                  'unsubscribed_at' => null,
                ]);
              }

              Notification::make()
                ->success()
                ->title('Subscribers resubscribed')
                ->send();
            })
            ->requiresConfirmation()
            ->deselectRecordsAfterCompletion(),
        ]),
      ])
      ->emptyStateHeading('No subscribers yet')
      ->emptyStateDescription('Start building your mailing list by adding subscribers or importing from a CSV file.')
      ->emptyStateIcon('heroicon-o-user-group');
  }

  /**
   * Import subscribers from CSV file
   */
  public static function importSubscribersFromCsv(string $csvPath, string $duplicateHandling, bool $sendWelcomeEmail = false): array
  {
    $imported = 0;
    $skipped = 0;
    $errors = 0;
    $errorMessages = [];

    try {
      $handle = fopen($csvPath, 'r');

      if (!$handle) {
        return ['imported' => 0, 'skipped' => 0, 'errors' => 1, 'messages' => ['Could not open CSV file']];
      }

      // Read header row
      $headers = fgetcsv($handle);

      if (!$headers || !in_array('email', $headers)) {
        fclose($handle);
        return ['imported' => 0, 'skipped' => 0, 'errors' => 1, 'messages' => ['CSV must contain an "email" column']];
      }

      // Map headers to indices
      $emailIndex = array_search('email', $headers);
      $nameIndex = array_search('name', $headers);
      $statusIndex = array_search('status', $headers);

      // Process each row
      $rowNumber = 1;
      while (($row = fgetcsv($handle)) !== false) {
        $rowNumber++;

        // Skip empty rows
        if (empty(array_filter($row))) {
          continue;
        }

        $email = trim($row[$emailIndex] ?? '');
        $name = trim($row[$nameIndex] ?? '');
        $status = trim($row[$statusIndex] ?? 'subscribed');

        // Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $errors++;
          $errorMessages[] = "Row {$rowNumber}: Invalid email '{$email}'";
          continue;
        }

        // Validate status
        if (!in_array($status, ['subscribed', 'unsubscribed'])) {
          $status = 'subscribed';
        }

        // Check for existing subscriber
        $existingSubscriber = Subscriber::where('email', $email)->first();

        if ($existingSubscriber) {
          if ($duplicateHandling === 'skip') {
            $skipped++;
            continue;
          } elseif ($duplicateHandling === 'error') {
            $errors++;
            $errorMessages[] = "Row {$rowNumber}: Email '{$email}' already exists";
            continue;
          } elseif ($duplicateHandling === 'update') {
            $existingSubscriber->update([
              'name' => $name ?: $existingSubscriber->name,
              'status' => $status,
              'unsubscribed_at' => $status === 'unsubscribed' ? now() : null,
            ]);
            $imported++;
            continue;
          }
        }

        // Create new subscriber
        try {
          Subscriber::create([
            'email' => $email,
            'name' => $name ?: null,
            'status' => $status,
            'unsubscribed_at' => $status === 'unsubscribed' ? now() : null,
            'preferences' => (new Subscriber())->getDefaultPreferences(),
          ]);

          $imported++;

          // TODO: Send welcome email if enabled
          // if ($sendWelcomeEmail && $status === 'subscribed') {
          //   // Send welcome email logic here
          // }

        } catch (\Exception $e) {
          $errors++;
          $errorMessages[] = "Row {$rowNumber}: Failed to create subscriber - " . $e->getMessage();
        }
      }

      fclose($handle);

    } catch (\Exception $e) {
      return ['imported' => 0, 'skipped' => 0, 'errors' => 1, 'messages' => ['Import failed: ' . $e->getMessage()]];
    }

    return [
      'imported' => $imported,
      'skipped' => $skipped,
      'errors' => $errors,
      'messages' => $errorMessages
    ];
  }

  public static function getRelations(): array
  {
    return [];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListSubscribers::route('/'),
      'create' => Pages\CreateSubscriber::route('/create'),
      'edit' => Pages\EditSubscriber::route('/{record}/edit'),
    ];
  }

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::where('status', 'subscribed')->count() ?: null;
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
