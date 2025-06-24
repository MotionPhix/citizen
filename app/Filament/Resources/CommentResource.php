<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommentResource extends Resource
{
  protected static ?string $model = Comment::class;
  protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
  protected static ?string $navigationGroup = 'Content';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Select::make('blog_id')
          ->relationship('blog', 'title')
          ->required(),

        Forms\Components\Select::make('user_id')
          ->relationship('user', 'name')
          ->required(),

        Forms\Components\Select::make('parent_id')
          ->label('Reply to')
          ->relationship('parent', 'content'),

        Forms\Components\Textarea::make('content')
          ->required()
          ->maxLength(65535)
          ->columnSpanFull(),

        Forms\Components\Toggle::make('is_approved')
          ->required(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('blog.title')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('user.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('content')
          ->limit(50),
        Tables\Columns\IconColumn::make('is_approved')
          ->boolean(),
        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable(),
      ])
      ->filters([
        Tables\Filters\TernaryFilter::make('is_approved'),
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

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListComments::route('/'),
      'create' => Pages\CreateComment::route('/create'),
      'edit' => Pages\EditComment::route('/{record}/edit'),
    ];
  }

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::count();
  }
}
