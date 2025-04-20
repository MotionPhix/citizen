<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryResource\Pages;
use App\Models\Story;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StoryResource extends Resource
{
  protected static ?string $model = Story::class;
  protected static ?string $navigationIcon = 'heroicon-o-book-open';
  protected static ?string $navigationGroup = 'Newsletter Content';
  protected static ?int $navigationSort = 2;

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

        Forms\Components\TextInput::make('excerpt')
          ->required()
          ->maxLength(255),

        Forms\Components\RichEditor::make('content')
          ->required()
          ->columnSpanFull(),

        Forms\Components\FileUpload::make('image')
          ->image()
          ->directory('newsletter/stories')
          ->imageEditor()
          ->columnSpanFull(),

        Forms\Components\TextInput::make('url')
          ->url()
          ->maxLength(255),

        Forms\Components\DateTimePicker::make('published_at')
          ->default(now()),

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

        Tables\Columns\TextColumn::make('excerpt')
          ->limit(50)
          ->searchable(),

        Tables\Columns\ImageColumn::make('image'),

        Tables\Columns\TextColumn::make('published_at')
          ->dateTime()
          ->sortable(),

        Tables\Columns\TextColumn::make('order')
          ->sortable(),
      ])
      ->defaultSort('order')
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
      'index' => Pages\ListStories::route('/'),
      'create' => Pages\CreateStory::route('/create'),
      'edit' => Pages\EditStory::route('/{record}/edit'),
    ];
  }
}
