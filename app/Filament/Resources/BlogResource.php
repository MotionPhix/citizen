<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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
        Forms\Components\Hidden::make('user_id')
          ->default(auth()->id()),

        Forms\Components\Section::make()
          ->schema([
            Forms\Components\TextInput::make('title')
              ->required()
              ->maxLength(255)
              ->live(onBlur: true)
              ->afterStateUpdated(function (string $state, Forms\Set $set) {
                $set('slug', Str::slug($state));
              }),

            Forms\Components\TextInput::make('slug')
              ->required()
              ->maxLength(255)
              ->unique(ignoreRecord: true)
              ->disabled(),

            Forms\Components\RichEditor::make('content')
              ->required()
              ->columnSpanFull()
              ->toolbarButtons([
                'attachFiles',
                'blockquote',
                'bold',
                'bulletList',
                'codeBlock',
                'h2',
                'h3',
                'italic',
                'link',
                'orderedList',
                'redo',
                'strike',
                'underline',
                'undo',
              ])
              ->fileAttachmentsDisk('public')
              ->fileAttachmentsDirectory('blog-attachments')
              ->fileAttachmentsVisibility('public'),

            Forms\Components\Textarea::make('excerpt')
              ->maxLength(500)
              ->rows(3)
              ->columnSpanFull()
              ->helperText('A brief description of the blog post (optional).'),

            Forms\Components\SpatieTagsInput::make('tags')
              ->columnSpanFull(),

            SpatieMediaLibraryFileUpload::make('featured_image')
              ->collection('blog_images')
              ->image()
              ->imageEditor()
              ->imageEditorAspectRatios([
                '16:9',
                '4:3',
              ])
              ->imageEditorViewportWidth('1920')
              ->imageEditorViewportHeight('1080')
              ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
              ->maxSize(5120)
              ->downloadable()
              ->columnSpanFull()
              ->helperText('Upload a featured image for the blog post. Recommended size: 1920x1080px.'),
          ])
          ->columnSpan(['lg' => 2]),

        Forms\Components\Section::make('Publishing')
          ->schema([
            Forms\Components\DateTimePicker::make('published_at')
              ->label('Publish Date')
              ->helperText('Leave empty to publish immediately when status is set to published.'),

            Forms\Components\Toggle::make('is_published')
              ->label('Published')
              ->default(false)
              ->helperText('Toggle to publish or unpublish this blog post.'),

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
        Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')
          ->label('Image')
          ->collection('blog_images')
          ->conversion('thumbnail')
          ->circular()
          ->defaultImageUrl(url('/images/placeholder-blog.jpg')),

        Tables\Columns\TextColumn::make('title')
          ->searchable()
          ->wrap()
          ->lineClamp(2)
          ->sortable(),

        Tables\Columns\TextColumn::make('user.name')
          ->label('Author')
          ->sortable()
          ->searchable(),

        Tables\Columns\TextColumn::make('is_published')
          ->label('Status')
          ->badge()
          ->formatStateUsing(fn (bool $state) => $state ? 'Published' : 'Draft')
          ->color(fn (bool $state): string => match ($state) {
            true => 'success',
            false => 'danger',
          }),

        Tables\Columns\TextColumn::make('published_at')
          ->label('Published')
          ->dateTime()
          ->sortable()
          ->toggleable(),

        Tables\Columns\TextColumn::make('view_count')
          ->label('Views')
          ->numeric()
          ->sortable()
          ->alignCenter()
          ->toggleable(),

        Tables\Columns\TextColumn::make('likes_count')
          ->label('Likes')
          ->numeric()
          ->sortable()
          ->alignCenter()
          ->toggleable(),

        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        Tables\Filters\TernaryFilter::make('is_published')
          ->label('Published')
          ->placeholder('All Posts')
          ->trueLabel('Published Posts')
          ->falseLabel('Draft Posts'),

        Tables\Filters\Filter::make('published_at')
          ->form([
            Forms\Components\DatePicker::make('published_from')
              ->label('Published from'),
            Forms\Components\DatePicker::make('published_until')
              ->label('Published until'),
          ])
          ->query(function (Builder $query, array $data): Builder {
            return $query
              ->when(
                $data['published_from'],
                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
              )
              ->when(
                $data['published_until'],
                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
              );
          })
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\ViewAction::make()
            ->url(fn (Blog $record): string => route('blogs.show', $record->slug))
            ->openUrlInNewTab(),

          Tables\Actions\EditAction::make(),

          Tables\Actions\DeleteAction::make(),
        ])
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
          Tables\Actions\BulkAction::make('togglePublish')
            ->label('Toggle Publish Status')
            ->icon('heroicon-o-arrow-path')
            ->action(function (Collection $records): void {
              foreach ($records as $record) {
                $record->update([
                  'is_published' => !$record->is_published,
                  'published_at' => $record->is_published ? null : now(),
                ]);
              }
            })
            ->deselectRecordsAfterCompletion()
            ->requiresConfirmation(),
        ]),
      ])
      ->emptyStateActions([
        Tables\Actions\CreateAction::make(),
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

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::count();
  }
}
