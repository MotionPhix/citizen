<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSubmissionResource\Pages;
use App\Filament\Resources\ContactSubmissionResource\RelationManagers;
use App\Models\ContactSubmission;
use App\Notifications\ContactFormResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactSubmissionResource extends Resource
{
  protected static ?string $model = ContactSubmission::class;
  protected static ?string $navigationIcon = 'heroicon-o-inbox';
  protected static ?string $navigationGroup = 'Communication';
  protected static ?int $navigationSort = 4;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make()
          ->schema([
            Forms\Components\TextInput::make('name')
              ->required()
              ->maxLength(255)
              ->disabled(),

            Forms\Components\TextInput::make('email')
              ->email()
              ->required()
              ->maxLength(255)
              ->disabled(),

            Forms\Components\TextInput::make('subject')
              ->required()
              ->maxLength(255)
              ->disabled(),

            Forms\Components\Textarea::make('message')
              ->required()
              ->disabled()
              ->columnSpanFull(),

            Forms\Components\Select::make('status')
              ->options([
                'unread' => 'Unread',
                'read' => 'Read',
                'replied' => 'Replied',
              ])
              ->required(),

            Forms\Components\Textarea::make('response')
              ->label('Your Response')
              ->required()
              ->columnSpanFull()
              ->visible(fn($record) => $record && $record->status !== 'replied'),
          ])
          ->columns(2),

        Forms\Components\Section::make('Additional Information')
          ->schema([
            Forms\Components\TextInput::make('ip_address')
              ->disabled(),

            Forms\Components\TextInput::make('user_agent')
              ->disabled(),

            Forms\Components\DateTimePicker::make('created_at')
              ->disabled(),

            Forms\Components\DateTimePicker::make('updated_at')
              ->disabled(),
          ])
          ->columns(2)
          ->collapsible(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable(),

        Tables\Columns\TextColumn::make('name')
          ->searchable(),

        Tables\Columns\TextColumn::make('email')
          ->searchable(),

        Tables\Columns\TextColumn::make('subject')
          ->searchable()
          ->limit(30),

        Tables\Columns\BadgeColumn::make('status')
          ->colors([
            'danger' => 'unread',
            'warning' => 'read',
            'success' => 'replied',
          ]),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        Tables\Filters\SelectFilter::make('status')
          ->options([
            'unread' => 'Unread',
            'read' => 'Read',
            'replied' => 'Replied',
          ]),
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\Action::make('reply')
          ->form([
            Forms\Components\Textarea::make('response')
              ->label('Your Response')
              ->required()
              ->maxLength(65535),
          ])
          ->action(function (ContactSubmission $record, array $data): void {
            $record->update([
              'response' => $data['response'],
              'status' => 'replied',
            ]);

            // Send email to the contact
            Notification::make()
              ->title('Response sent successfully')
              ->success()
              ->send();

            // Send the email using your existing notification system
            $record->notify(new ContactFormResponse($record, $data['response']));
          })
          ->visible(fn($record) => $record->status !== 'replied')
          ->icon('heroicon-o-paper-airplane')
          ->color('success'),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListContactSubmissions::route('/'),
      'create' => Pages\CreateContactSubmission::route('/create'),
      'edit' => Pages\EditContactSubmission::route('/{record}/edit'),
      'view' => Pages\ViewContactSubmission::route('/{record}'),
    ];
  }

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::where('status', 'unread')->count() ?: null;
  }
}
