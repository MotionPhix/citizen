<?php

namespace App\Filament\Resources\NewsletterIssueResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
  protected static string $relationship = 'events';
  protected static ?string $recordTitleAttribute = 'title';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('title')
          ->required()
          ->maxLength(255),

        Forms\Components\Textarea::make('description')
          ->required(),

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
          ->columnSpanFull(),

        Forms\Components\TextInput::make('capacity')
          ->numeric()
          ->minValue(0),

        Forms\Components\TextInput::make('order')
          ->numeric()
          ->default(0),
      ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('title')
          ->searchable(),
        Tables\Columns\TextColumn::make('location'),
        Tables\Columns\TextColumn::make('start_date')
          ->dateTime(),
        Tables\Columns\TextColumn::make('capacity'),
        Tables\Columns\TextColumn::make('order')
          ->sortable(),
      ])
      ->defaultSort('order')
      ->reorderable('order')
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
}
