<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterContentResource\Pages;
use App\Models\NewsletterContent;
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

class NewsletterContentResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = NewsletterContent::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Newsletter';
    protected static ?string $navigationLabel = 'Content';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Newsletter Assignment')
                    ->schema([
                        Forms\Components\Select::make('newsletter_issue_id')
                            ->relationship('newsletterIssue', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->placeholder('e.g., Weekly Update - March 2024'),
                                Forms\Components\Textarea::make('description')
                                    ->rows(3),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->default(now()),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $issue = NewsletterIssue::create($data);

                                Notification::make()
                                    ->success()
                                    ->title('Newsletter issue created')
                                    ->body('You can now add content to this issue.')
                                    ->send();

                                return $issue->id;
                            })
                            ->helperText('Select which newsletter issue this content belongs to'),

                        Forms\Components\Placeholder::make('newsletter_info')
                            ->label('Newsletter Info')
                            ->content(function (Forms\Get $get): string {
                                if (!$get('newsletter_issue_id')) {
                                    return 'Select a newsletter to see details';
                                }

                                $issue = NewsletterIssue::find($get('newsletter_issue_id'));
                                if (!$issue) {
                                    return 'Newsletter not found';
                                }

                                $contentCount = $issue->contents()->count();
                                return "Status: {$issue->status} • Content pieces: {$contentCount} • Publish date: " . $issue->published_at?->format('M j, Y');
                            })
                            ->reactive(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Content Details')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options(NewsletterContent::getTypes())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('category', null)),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('excerpt')
                            ->maxLength(500)
                            ->rows(3)
                            ->visible(fn ($get) => in_array($get('type'), ['story', 'update', 'announcement']))
                            ->columnSpanFull()
                            ->helperText('Brief description that appears in the newsletter preview'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Content Body')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'blockquote',
                                'codeBlock',
                            ])
                            ->columnSpanFull(),

                        SpatieMediaLibraryFileUpload::make('image')
                            ->collection('images')
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('url')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com')
                            ->columnSpanFull(),
                    ]),

                // Event-specific fields
                Forms\Components\Section::make('Event Details')
                    ->schema([
                        Forms\Components\TextInput::make('metadata.location')
                            ->label('Location')
                            ->maxLength(255)
                            ->placeholder('Conference Center, Main Hall'),

                        Forms\Components\TextInput::make('metadata.capacity')
                            ->label('Capacity')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('100'),

                        Forms\Components\DateTimePicker::make('metadata.start_date')
                            ->label('Start Date & Time')
                            ->required(fn ($get) => $get('type') === 'event'),

                        Forms\Components\DateTimePicker::make('metadata.end_date')
                            ->label('End Date & Time')
                            ->after('metadata.start_date'),

                        Forms\Components\TextInput::make('metadata.registration_url')
                            ->label('Registration URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com/register')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => $get('type') === 'event')
                    ->collapsible(),

                // Story-specific fields
                Forms\Components\Section::make('Story Details')
                    ->schema([
                        Forms\Components\TextInput::make('metadata.author')
                            ->label('Author')
                            ->maxLength(255)
                            ->placeholder('John Doe'),

                        Forms\Components\TextInput::make('metadata.read_time')
                            ->label('Reading Time (minutes)')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('5'),

                        Forms\Components\Select::make('metadata.source')
                            ->label('Source')
                            ->options([
                                'internal' => 'Internal',
                                'external' => 'External',
                                'partner' => 'Partner',
                                'community' => 'Community',
                            ])
                            ->placeholder('Select source'),
                    ])
                    ->columns(3)
                    ->visible(fn ($get) => $get('type') === 'story')
                    ->collapsible(),

                // Update-specific fields
                Forms\Components\Section::make('Update Details')
                    ->schema([
                        Forms\Components\Select::make('category')
                            ->options(NewsletterContent::getCategories())
                            ->required(fn ($get) => $get('type') === 'update')
                            ->placeholder('Select category'),

                        Forms\Components\Select::make('metadata.priority')
                            ->label('Priority')
                            ->options([
                                'low' => 'Low',
                                'normal' => 'Normal',
                                'high' => 'High',
                                'urgent' => 'Urgent',
                            ])
                            ->default('normal'),

                        Forms\Components\DatePicker::make('metadata.effective_date')
                            ->label('Effective Date')
                            ->placeholder('When does this update take effect?'),
                    ])
                    ->columns(3)
                    ->visible(fn ($get) => in_array($get('type'), ['update', 'announcement']))
                    ->collapsible(),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->default(now())
                            ->required(),

                        Forms\Components\TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Content')
                            ->helperText('Featured content appears prominently in newsletters'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('newsletterIssue.title')
                    ->label('Newsletter')
                    ->searchable()
                    ->sortable()
                    ->description(fn (NewsletterContent $record): string =>
                        "Status: {$record->newsletterIssue->status} • " .
                        $record->newsletterIssue->published_at?->format('M j, Y')
                    )
                    ->url(fn (NewsletterContent $record): string =>
                        route('filament.admin.resources.newsletter-issues.edit', $record->newsletterIssue)
                    )
                    ->color('primary'),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'story',
                        'success' => 'event',
                        'warning' => 'update',
                        'danger' => 'announcement',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->description(fn (NewsletterContent $record): ?string =>
                        $record->excerpt ? str($record->excerpt)->limit(60) : null
                    ),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection('images')
                    ->conversion('thumbnail')
                    ->circular()
                    ->label('Image')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('order')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('newsletter_issue_id')
                    ->relationship('newsletterIssue', 'title')
                    ->label('Newsletter Issue')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('type')
                    ->options(NewsletterContent::getTypes())
                    ->multiple(),

                Tables\Filters\SelectFilter::make('category')
                    ->options(NewsletterContent::getCategories())
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Content'),

                Tables\Filters\Filter::make('published')
                    ->query(fn (Builder $query): Builder => $query->where('published_at', '<=', now()))
                    ->label('Published'),

                Tables\Filters\Filter::make('scheduled')
                    ->query(fn (Builder $query): Builder => $query->where('published_at', '>', now()))
                    ->label('Scheduled'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('view_newsletter')
                        ->label('View Newsletter')
                        ->icon('heroicon-o-envelope')
                        ->color('primary')
                        ->url(fn (NewsletterContent $record): string =>
                            route('filament.admin.resources.newsletter-issues.edit', $record->newsletterIssue)
                        ),

                    Tables\Actions\EditAction::make()
                        ->modalWidth(MaxWidth::FourExtraLarge),

                    Tables\Actions\DeleteAction::make(),

                    Tables\Actions\Action::make('duplicate')
                        ->label('Duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->form([
                            Forms\Components\Select::make('newsletter_issue_id')
                                ->label('Target Newsletter')
                                ->relationship('newsletterIssue', 'title')
                                ->default(fn (NewsletterContent $record) => $record->newsletter_issue_id)
                                ->required(),
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->default(fn (NewsletterContent $record) => $record->title . ' (Copy)'),
                        ])
                        ->action(function (NewsletterContent $record, array $data): void {
                            $newRecord = $record->replicate();
                            $newRecord->newsletter_issue_id = $data['newsletter_issue_id'];
                            $newRecord->title = $data['title'];
                            $newRecord->save();

                            Notification::make()
                                ->success()
                                ->title('Content duplicated')
                                ->send();
                        }),
                ])
                    ->label('Actions')
                    ->icon('heroicon-o-ellipsis-vertical'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('feature')
                        ->label('Mark as Featured')
                        ->icon('heroicon-o-star')
                        ->action(fn ($records) => $records->each->update(['is_featured' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('unfeature')
                        ->label('Remove Featured')
                        ->icon('heroicon-o-star')
                        ->action(fn ($records) => $records->each->update(['is_featured' => false]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('move_to_newsletter')
                        ->label('Move to Newsletter')
                        ->icon('heroicon-o-arrow-right')
                        ->form([
                            Forms\Components\Select::make('newsletter_issue_id')
                                ->label('Target Newsletter')
                                ->relationship('newsletterIssue', 'title')
                                ->required(),
                        ])
                        ->action(function ($records, array $data): void {
                            $records->each->update(['newsletter_issue_id' => $data['newsletter_issue_id']]);

                            Notification::make()
                                ->success()
                                ->title('Content moved')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->reorderable('order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterContents::route('/'),
            'create' => Pages\CreateNewsletterContent::route('/create'),
            'edit' => Pages\EditNewsletterContent::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereHas('newsletterIssue', function ($query) {
            $query->where('status', 'draft');
        })->count() ?: null;
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['newsletterIssue']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'excerpt', 'content', 'newsletterIssue.title'];
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
