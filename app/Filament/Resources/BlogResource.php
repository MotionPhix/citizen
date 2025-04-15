<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
  protected static ?string $model = Blog::class;
  protected static ?string $navigationIcon = 'heroicon-o-document-text';
  protected static ?string $navigationGroup = 'Content';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        // Add this hidden field at the beginning of your schema
        Forms\Components\Hidden::make('user_id')
          ->default(auth()->id()),

        Forms\Components\Section::make()
          ->schema([
            Forms\Components\TextInput::make('title')
              ->required()
              ->maxLength(255)
              ->live(onBlur: true)
              ->afterStateUpdated(fn (string $state, Forms\Set $set) => $set('slug', Str::slug($state))),

            Forms\Components\TextInput::make('slug')
              ->required()
              ->maxLength(255)
              ->unique(ignoreRecord: true),

            Forms\Components\MarkdownEditor::make('content')
              ->required()
              ->columnSpanFull(),

            Forms\Components\TextInput::make('excerpt')
              ->maxLength(255)
              ->columnSpanFull(),

            Forms\Components\SpatieTagsInput::make('tags')
              ->columnSpanFull(),

            /*Forms\Components\FileUpload::make('blog_images')
              ->image()
              ->imageEditor()
              ->columnSpanFull(),*/

            FileUpload::make('blog_images')
              ->label('Blog Images')
              ->multiple()
              ->image()
              ->imageEditor()
              ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
              ->maxSize(5120) // 5MB
              ->directory('blog-images/' . now()->format('Y/m'))
              ->preserveFilenames()
              ->reorderable()
              ->columnSpanFull()
              ->helperText('Upload images in JPEG, PNG, WebP, or GIF format. Maximum size: 5MB'),
          ])
          ->columnSpan(['lg' => 2]),

        Forms\Components\Section::make()
          ->schema([
            Forms\Components\DateTimePicker::make('published_at')
              ->label('Publish Date'),

            Forms\Components\Toggle::make('is_published')
              ->label('Published')
              ->default(false),

            Forms\Components\Placeholder::make('created_at')
              ->label('Created Date')
              ->content(fn (?Blog $record): string => $record?->created_at?->diffForHumans() ?? '-'),

            Forms\Components\Placeholder::make('updated_at')
              ->label('Last Modified Date')
              ->content(fn (?Blog $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
          ])
          ->columnSpan(['lg' => 1]),
      ])
      ->columns(3);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\ImageColumn::make('featured_image.thumbnail')
          ->label('Image'),
        Tables\Columns\TextColumn::make('title')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('published_at')
          ->dateTime()
          ->sortable(),
        Tables\Columns\IconColumn::make('is_published')
          ->boolean()
          ->sortable(),
        Tables\Columns\TextColumn::make('view_count')
          ->sortable(),
      ])
      ->filters([
        Tables\Filters\TernaryFilter::make('is_published')
          ->label('Published')
          ->placeholder('All Posts')
          ->trueLabel('Published Posts')
          ->falseLabel('Draft Posts'),
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
      'index' => Pages\ListBlogs::route('/'),
      'create' => Pages\CreateBlog::route('/create'),
      'edit' => Pages\EditBlog::route('/{record}/edit'),
    ];
  }
}
