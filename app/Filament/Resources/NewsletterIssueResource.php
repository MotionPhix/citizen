<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterIssueResource\Pages;
use App\Filament\Resources\NewsletterIssueResource\RelationManagers\EventsRelationManager;
use App\Filament\Resources\NewsletterIssueResource\RelationManagers\StoriesRelationManager;
use App\Filament\Resources\NewsletterIssueResource\RelationManagers\UpdatesRelationManager;
use App\Models\NewsletterIssue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class NewsletterIssueResource extends Resource
{
  protected static ?string $model = NewsletterIssue::class;
  protected static ?string $navigationIcon = 'heroicon-o-envelope';
  protected static ?string $navigationGroup = 'Communication';
  protected static ?int $navigationSort = 2;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make()
          ->schema([
            Forms\Components\TextInput::make('title')
              ->required()
              ->maxLength(255),

            Forms\Components\Textarea::make('description')
              ->maxLength(65535)
              ->columnSpanFull(),

            SpatieMediaLibraryFileUpload::make('featured_image')
              ->collection('featured_images')
              ->image()
              ->imageEditor()
              ->maxSize(5120)
              ->columnSpanFull(),

            Forms\Components\DateTimePicker::make('published_at')
              ->required(),

            Forms\Components\Select::make('status')
              ->options([
                'draft' => 'Draft',
                'scheduled' => 'Scheduled',
                'published' => 'Published',
              ])
              ->required()
              ->default('draft'),
          ])
          ->columnSpan(['lg' => 2]),

        Forms\Components\Section::make()
          ->schema([
            Forms\Components\Placeholder::make('created_at')
              ->label('Created at')
              ->content(fn (?NewsletterIssue $record): string => $record?->created_at?->diffForHumans() ?? '-'),

            Forms\Components\Placeholder::make('updated_at')
              ->label('Last modified at')
              ->content(fn (?NewsletterIssue $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
          ])
          ->columnSpan(['lg' => 1]),
      ])
      ->columns(3);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('title')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('description')
          ->limit(50)
          ->searchable(),

        Tables\Columns\BadgeColumn::make('status')
          ->colors([
            'danger' => 'draft',
            'warning' => 'scheduled',
            'success' => 'published',
          ]),

        Tables\Columns\TextColumn::make('published_at')
          ->dateTime()
          ->sortable(),

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
            'published' => 'Published',
          ]),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('preview')
          ->url(fn (NewsletterIssue $record): string => route('newsletter.preview', $record))
          ->openUrlInNewTab()
          ->icon('heroicon-o-eye'),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      StoriesRelationManager::class,
      UpdatesRelationManager::class,
      EventsRelationManager::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListNewsletterIssues::route('/'),
      'create' => Pages\CreateNewsletterIssue::route('/create'),
      'edit' => Pages\EditNewsletterIssue::route('/{record}/edit'),
    ];
  }
}
