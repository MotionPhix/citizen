<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UpdateResource\Pages;
use App\Models\Update;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UpdateResource extends Resource
{
  protected static ?string $model = Update::class;
  protected static ?string $navigationIcon = 'heroicon-o-newspaper';
  protected static ?string $navigationGroup = 'Newsletter';
  protected static ?int $navigationSort = 3;

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
          ->columnSpanFull(),

        Forms\Components\FileUpload::make('image')
          ->image()
          ->directory('newsletter/updates')
          ->imageEditor()
          ->columnSpanFull(),

        Forms\Components\TextInput::make('url')
          ->url()
          ->maxLength(255),

        Forms\Components\Select::make('category')
          ->options([
            'announcements' => 'Announcements',
            'events' => 'Events',
            'news' => 'News',
            'updates' => 'Updates',
          ])
          ->searchable(),

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

        Tables\Columns\TextColumn::make('category')
          ->badge()
          ->sortable(),

        Tables\Columns\ImageColumn::make('image'),

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

        Tables\Filters\SelectFilter::make('category')
          ->options([
            'announcements' => 'Announcements',
            'events' => 'Events',
            'news' => 'News',
            'updates' => 'Updates',
          ]),
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
      'index' => Pages\ListUpdates::route('/'),
      'create' => Pages\CreateUpdate::route('/create'),
      'edit' => Pages\EditUpdate::route('/{record}/edit'),
    ];
  }
}
