<?php

namespace App\Filament\Resources\NewsletterIssueResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StoriesRelationManager extends RelationManager
{
  protected static string $relationship = 'stories';
  protected static ?string $recordTitleAttribute = 'title';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('title')
          ->required()
          ->maxLength(255),

        Forms\Components\Textarea::make('excerpt')
          ->required()
          ->maxLength(255),

        Forms\Components\RichEditor::make('content')
          ->required()
          ->columnSpanFull(),

        Forms\Components\FileUpload::make('image')
          ->image()
          ->directory('newsletter/stories')
          ->columnSpanFull(),

        Forms\Components\TextInput::make('url')
          ->url()
          ->maxLength(255),

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
        Tables\Columns\TextColumn::make('excerpt')
          ->limit(50),
        Tables\Columns\ImageColumn::make('image'),
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
