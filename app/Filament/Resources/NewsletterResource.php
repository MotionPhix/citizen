<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterResource\Pages;
use App\Filament\Resources\NewsletterResource\RelationManagers;
use App\Models\Newsletter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewsletterResource extends Resource
{
  protected static ?string $model = Newsletter::class;
  protected static ?string $navigationIcon = 'heroicon-o-envelope';
  protected static ?string $navigationGroup = 'Communication';
  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make()
          ->schema([
            Forms\Components\TextInput::make('title')
              ->required()
              ->maxLength(255),

            Forms\Components\RichEditor::make('content')
              ->required()
              ->columnSpanFull(),

            Forms\Components\Select::make('status')
              ->options([
                'draft' => 'Draft',
                'scheduled' => 'Scheduled',
                'sent' => 'Sent',
              ])
              ->default('draft')
              ->disabled(fn($record) => $record && $record->status === 'sent')
              ->required(),

            Forms\Components\DateTimePicker::make('scheduled_for')
              ->label('Schedule Send Time')
              ->helperText('Leave empty to send immediately')
              ->visible(fn($get) => $get('status') === 'scheduled')
              ->after('now'),

            Forms\Components\Placeholder::make('sent_at')
              ->label('Sent At')
              ->content(fn($record) => $record?->sent_at?->diffForHumans() ?? '-')
              ->visible(fn($record) => $record && $record->status === 'sent'),

            Forms\Components\Placeholder::make('subscriber_count')
              ->label('Active Subscribers')
              ->content(fn() => \App\Models\Subscriber::active()->count() . ' subscribers'),
          ])
          ->columns(2),

        Forms\Components\Section::make('Preview & Test')
          ->schema([
            Forms\Components\TextInput::make('test_email')
              ->label('Send Test To')
              ->email()
              ->helperText('Send a test email to this address'),

            Forms\Components\Button::make('send_test')
              ->label('Send Test Email')
              ->action(function ($livewire, $data) {
                // Implement test email sending logic
              })
              ->visible(fn($record) => $record && $record->status !== 'sent'),
          ])
          ->collapsible(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('title')
          ->searchable()
          ->sortable(),

        Tables\Columns\BadgeColumn::make('status')
          ->colors([
            'gray' => 'draft',
            'warning' => 'scheduled',
            'success' => 'sent',
            'danger' => 'failed',
          ]),

        Tables\Columns\TextColumn::make('scheduled_for')
          ->dateTime()
          ->sortable()
          ->visible(fn ($record) => $record->status === 'scheduled'),

        Tables\Columns\TextColumn::make('sent_at')
          ->dateTime()
          ->sortable()
          ->visible(fn ($record) => $record->status === 'sent'),

        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('status')
          ->options([
            'draft' => 'Draft',
            'scheduled' => 'Scheduled',
            'sent' => 'Sent',
            'failed' => 'Failed',
          ]),
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\ViewAction::make(),
          Tables\Actions\EditAction::make(),
          Tables\Actions\Action::make('send')
            ->label('Send Now')
            ->icon('heroicon-o-paper-airplane')
            ->requiresConfirmation()
            ->action(function (Newsletter $record) {
              // Implement newsletter sending logic
            })
            ->visible(fn ($record) => $record->status !== 'sent'),
          Tables\Actions\Action::make('duplicate')
            ->icon('heroicon-o-document-duplicate')
            ->action(function (Newsletter $record) {
              $clone = $record->replicate();
              $clone->title = "Copy of " . $clone->title;
              $clone->status = 'draft';
              $clone->scheduled_for = null;
              $clone->sent_at = null;
              $clone->save();
            }),
        ]),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
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
      'index' => Pages\ListNewsletters::route('/'),
      'create' => Pages\CreateNewsletter::route('/create'),
      'edit' => Pages\EditNewsletter::route('/{record}/edit'),
      'view' => Pages\ViewNewsletter::route('/{record}'),
    ];
  }
}
