<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriberResource\Pages;
use App\Filament\Resources\SubscriberResource\RelationManagers;
use App\Filament\Widgets\SubscriberStats;
use App\Models\Subscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriberResource extends Resource
{
  protected static ?string $model = Subscriber::class;
  protected static ?string $navigationIcon = 'heroicon-o-users';
  protected static ?string $navigationGroup = 'Communication';
  protected static ?int $navigationSort = 3;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('email')
          ->email()
          ->required()
          ->maxLength(255)
          ->unique(ignoreRecord: true),

        Forms\Components\TextInput::make('name')
          ->maxLength(255),

        Forms\Components\Select::make('status')
          ->options([
            'subscribed' => 'Subscribed',
            'unsubscribed' => 'Unsubscribed',
          ])
          ->required(),

        Forms\Components\DateTimePicker::make('unsubscribed_at')
          ->visible(fn ($get) => $get('status') === 'unsubscribed'),
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
          ]),

        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('status')
          ->options([
            'subscribed' => 'Subscribed',
            'unsubscribed' => 'Unsubscribed',
          ]),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('unsubscribe')
          ->icon('heroicon-o-x-circle')
          ->requiresConfirmation()
          ->action(fn (Subscriber $record) => $record->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
          ]))
          ->visible(fn ($record) => $record->status === 'subscribed'),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
          Tables\Actions\BulkAction::make('unsubscribe')
            ->label('Unsubscribe Selected')
            ->icon('heroicon-o-x-circle')
            ->requiresConfirmation()
            ->action(fn (Collection $records) => $records->each->update([
              'status' => 'unsubscribed',
              'unsubscribed_at' => now(),
            ])),
        ]),
      ])
      ->defaultSort('created_at', 'desc');
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
      'index' => Pages\ListSubscribers::route('/'),
      'create' => Pages\CreateSubscriber::route('/create'),
      'edit' => Pages\EditSubscriber::route('/{record}/edit'),
    ];
  }

  public static function getWidgets(): array
  {
    return [
      SubscriberStats::class,
    ];
  }
}
