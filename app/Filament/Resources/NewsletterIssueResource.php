<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterIssueResource\Pages;
use App\Filament\Resources\NewsletterIssueResource\RelationManagers\ContentsRelationManager;
use App\Models\NewsletterIssue;
use Filament\Forms;
use Filament\Forms\Form;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;

class NewsletterIssueResource extends Resource implements HasShieldPermissions
{
  protected static ?string $model = NewsletterIssue::class;
  protected static ?string $navigationIcon = 'heroicon-o-envelope';
  protected static ?string $navigationGroup = 'Newsletter';
  protected static ?string $navigationLabel = 'Issues';
  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Newsletter Details')
          ->schema([
            Forms\Components\TextInput::make('title')
              ->required()
              ->maxLength(255)
              ->placeholder('e.g., Weekly Update - March 2024')
              ->helperText('This will be the main title of your newsletter'),

            Forms\Components\Textarea::make('description')
              ->maxLength(65535)
              ->rows(4)
              ->placeholder('Brief description of what this newsletter contains...')
              ->helperText('Internal description to help you identify this newsletter issue')
              ->columnSpanFull(),

            SpatieMediaLibraryFileUpload::make('featured_image')
              ->collection('featured_images')
              ->image()
              ->imageEditor()
              ->maxSize(5120)
              ->columnSpanFull()
              ->helperText('Optional header image for the newsletter'),
          ])
          ->columnSpan(['lg' => 2]),

        Forms\Components\Section::make('Publishing Settings')
          ->schema([
            Forms\Components\DateTimePicker::make('published_at')
              ->required()
              ->default(now())
              ->helperText('When should this newsletter be published?'),

            Forms\Components\Select::make('status')
              ->options([
                'draft' => 'Draft',
                'scheduled' => 'Scheduled',
                'published' => 'Published',
              ])
              ->required()
              ->default('draft')
              ->helperText('Current status of this newsletter'),

            Forms\Components\Placeholder::make('content_summary')
              ->label('Content Summary')
              ->content(function (?NewsletterIssue $record): string {
                if (!$record) {
                  return 'Save the newsletter to see content summary';
                }

                $contents = $record->contents;
                if ($contents->isEmpty()) {
                  return 'No content added yet';
                }

                $summary = [];
                $types = $contents->groupBy('type');

                foreach ($types as $type => $items) {
                  $count = $items->count();
                  $featured = $items->where('is_featured', true)->count();
                  $label = ucfirst($type) . ($count > 1 ? 's' : '');
                  $summary[] = $count . ' ' . $label . ($featured > 0 ? " ({$featured} featured)" : '');
                }

                return implode(' • ', $summary);
              }),

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
        Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')
          ->collection('featured_images')
          ->conversion('thumbnail')
          ->circular()
          ->label('Image'),

        Tables\Columns\TextColumn::make('title')
          ->searchable()
          ->sortable()
          ->description(fn (NewsletterIssue $record): ?string => $record->description ? str($record->description)->limit(60) : null),

        Tables\Columns\TextColumn::make('contents_summary')
          ->label('Content')
          ->getStateUsing(function (NewsletterIssue $record): string {
            $contents = $record->contents;
            if ($contents->isEmpty()) {
              return 'No content';
            }

            $summary = [];
            $types = $contents->groupBy('type');

            foreach ($types as $type => $items) {
              $count = $items->count();
              $summary[] = $count . ' ' . ucfirst($type) . ($count > 1 ? 's' : '');
            }

            return implode(', ', $summary);
          })
          ->badge()
          ->color('gray'),

        Tables\Columns\BadgeColumn::make('status')
          ->colors([
            'danger' => 'draft',
            'warning' => 'scheduled',
            'success' => 'published',
          ])
          ->sortable(),

        Tables\Columns\TextColumn::make('published_at')
          ->label('Publish Date')
          ->dateTime('M j, Y g:i A')
          ->sortable()
          ->description(fn (NewsletterIssue $record): string => $record->published_at?->diffForHumans() ?? ''),

        Tables\Columns\TextColumn::make('created_at')
          ->label('Created')
          ->dateTime('M j, Y')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        Tables\Filters\SelectFilter::make('status')
          ->options([
            'draft' => 'Draft',
            'scheduled' => 'Scheduled',
            'published' => 'Published',
          ]),

        Tables\Filters\Filter::make('has_content')
          ->label('Has Content')
          ->query(fn (Builder $query): Builder => $query->whereHas('contents'))
          ->toggle(),

        Tables\Filters\Filter::make('recent')
          ->label('Recent (Last 30 days)')
          ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30)))
          ->toggle(),
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\Action::make('preview')
            ->label('Preview Newsletter')
            ->icon('heroicon-o-eye')
            ->color('gray')
            ->url(fn (NewsletterIssue $record): string => route('newsletter.preview', $record))
            ->openUrlInNewTab(),

          Tables\Actions\EditAction::make(),

          Tables\Actions\Action::make('duplicate')
            ->label('Duplicate Issue')
            ->icon('heroicon-o-document-duplicate')
            ->color('gray')
            ->form([
              Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->default(fn (NewsletterIssue $record) => $record->title . ' (Copy)'),
              Forms\Components\Toggle::make('copy_content')
                ->label('Copy all content')
                ->default(true)
                ->helperText('Include all content from the original newsletter'),
            ])
            ->action(function (NewsletterIssue $record, array $data): void {
              $newIssue = $record->replicate();
              $newIssue->title = $data['title'];
              $newIssue->status = 'draft';
              $newIssue->published_at = now();
              $newIssue->save();

              if ($data['copy_content']) {
                foreach ($record->contents as $content) {
                  $newContent = $content->replicate();
                  $newContent->newsletter_issue_id = $newIssue->id;
                  $newContent->save();
                }
              }

              Notification::make()
                ->success()
                ->title('Newsletter duplicated')
                ->body($data['copy_content'] ? 'Newsletter and all content copied successfully.' : 'Newsletter duplicated without content.')
                ->send();
            }),

          Tables\Actions\Action::make('quick_publish')
            ->label('Quick Publish')
            ->icon('heroicon-o-paper-airplane')
            ->color('success')
            ->action(function (NewsletterIssue $record): void {
              $record->update([
                'status' => 'published',
                'published_at' => now(),
              ]);

              Notification::make()
                ->success()
                ->title('Newsletter published')
                ->body('The newsletter has been published successfully.')
                ->send();
            })
            ->requiresConfirmation()
            ->visible(fn (NewsletterIssue $record): bool => $record->status === 'draft' && $record->contents()->exists()),

          Tables\Actions\DeleteAction::make(),
        ])
          ->label('Actions')
          ->icon('heroicon-o-ellipsis-vertical'),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),

          Tables\Actions\BulkAction::make('bulk_publish')
            ->label('Publish Selected')
            ->icon('heroicon-o-paper-airplane')
            ->color('success')
            ->action(function ($records): void {
              foreach ($records as $record) {
                if ($record->status === 'draft' && $record->contents()->exists()) {
                  $record->update([
                    'status' => 'published',
                    'published_at' => now(),
                  ]);
                }
              }

              Notification::make()
                ->success()
                ->title('Newsletters published')
                ->send();
            })
            ->requiresConfirmation()
            ->deselectRecordsAfterCompletion(),
        ]),
      ])
      ->emptyStateHeading('No newsletter issues yet')
      ->emptyStateDescription('Create your first newsletter issue to start building your email campaigns.')
      ->emptyStateIcon('heroicon-o-envelope')
      ->emptyStateActions([
        Tables\Actions\CreateAction::make()
          ->label('Create First Newsletter'),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      ContentsRelationManager::class,
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

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::where('status', 'draft')->count() ?: null;
  }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
        ];
    }
}
