<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
  protected static ?string $model = Event::class;
  protected static ?string $navigationIcon = 'heroicon-o-calendar';
  protected static ?string $navigationGroup = 'Newsletter Content';
  protected static ?int $navigationSort = 4;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Select::make('newsletter_issue_id')
          ->relationship('newsletterIssue', 'title')
          ->required()
          ->searchable()
          ->preload(),

        Forms\Components\TextInput::make('title')
          ->required()
          ->maxLength(255),

        Forms\Components\Textarea::make('description')
          ->required()
          ->rows(3),

        Forms\Components\TextInput::make('location')
          ->required()
          ->maxLength(255),

        Forms\Components\DateTimePicker::make('start_date')
          ->required(),

        Forms\Components\DateTimePicker::make('end_date'),

        Forms\Components\TextInput::make('registration_url')
          ->url()
          ->maxLength(255),

        Forms\Components\FileUpload::make('image')
          ->image()
          ->directory('newsletter/events')
          ->imageEditor()
          ->columnSpanFull(),

        Forms\Components\TextInput::make('capacity')
          ->numeric()
          ->minValue(0),

        Forms\Components\TextInput::make('order')
          ->numeric()
          ->default(0),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('newsletterIssue.title')
          ->label('Newsletter')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('title')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('location')
          ->searchable(),

        Tables\Columns\TextColumn::make('start_date')
          ->dateTime()
          ->sortable(),

        Tables\Columns\TextColumn::make('capacity'),

        Tables\Columns\TextColumn::make('order')
          ->sortable(),
      ])
      ->defaultSort('start_date')
      ->filters([
        Tables\Filters\SelectFilter::make('newsletter_issue_id')
          ->relationship('newsletterIssue', 'title')
          ->label('Newsletter Issue')
          ->searchable()
          ->preload(),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListEvents::route('/'),
      'create' => Pages\CreateEvent::route('/create'),
      'edit' => Pages\EditEvent::route('/{record}/edit'),
    ];
  }
}
