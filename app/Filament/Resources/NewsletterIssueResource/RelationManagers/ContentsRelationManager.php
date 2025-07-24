<?php

namespace App\Filament\Resources\NewsletterIssueResource\RelationManagers;

use App\Models\NewsletterContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;

class ContentsRelationManager extends RelationManager
{
    protected static string $relationship = 'contents';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $title = 'Newsletter Content';
    protected static ?string $icon = 'heroicon-o-document-text';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

                        Forms\Components\RichEditor::make('content')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'link',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'blockquote',
                            ])
                            ->columnSpanFull(),

                        SpatieMediaLibraryFileUpload::make('image')
                            ->collection('images')
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull()
                            ->helperText('Featured image for this content piece'),

                        Forms\Components\TextInput::make('url')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com')
                            ->columnSpanFull()
                            ->helperText('Optional link for "Read More" or external content'),
                    ])
                    ->columns(2),

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
                        Forms\Components\TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Content')
                            ->helperText('Featured content appears prominently'),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->default(now())
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width('60px'),

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
                    ->limit(50)
                    ->description(fn (NewsletterContent $record): ?string => $record->excerpt ? str($record->excerpt)->limit(60) : null),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection('images')
                    ->conversion('thumbnail')
                    ->circular()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(NewsletterContent::getTypes())
                    ->multiple(),

                Tables\Filters\SelectFilter::make('category')
                    ->options(NewsletterContent::getCategories())
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Content'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Content')
                    ->icon('heroicon-o-plus')
                    ->modalWidth(MaxWidth::FourExtraLarge)
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Content added successfully')
                            ->body('The content has been added to this newsletter issue.')
                    ),

                Tables\Actions\Action::make('quick_add_story')
                    ->label('Quick Add Story')
                    ->icon('heroicon-o-newspaper')
                    ->color('primary')
                    ->form([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('excerpt')
                            ->rows(3)
                            ->maxLength(500),
                        Forms\Components\TextInput::make('metadata.author')
                            ->label('Author')
                            ->placeholder('Author name'),
                    ])
                    ->action(function (array $data, RelationManager $livewire): void {
                        $livewire->getOwnerRecord()->contents()->create([
                            'type' => 'story',
                            'title' => $data['title'],
                            'excerpt' => $data['excerpt'] ?? null,
                            'metadata' => ['author' => $data['metadata']['author'] ?? null],
                            'published_at' => now(),
                            'order' => $livewire->getOwnerRecord()->contents()->max('order') + 1,
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Story added')
                            ->send();
                    }),

                Tables\Actions\Action::make('quick_add_event')
                    ->label('Quick Add Event')
                    ->icon('heroicon-o-calendar')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('metadata.location')
                            ->label('Location')
                            ->required(),
                        Forms\Components\DateTimePicker::make('metadata.start_date')
                            ->label('Start Date')
                            ->required(),
                    ])
                    ->action(function (array $data, RelationManager $livewire): void {
                        $livewire->getOwnerRecord()->contents()->create([
                            'type' => 'event',
                            'title' => $data['title'],
                            'metadata' => [
                                'location' => $data['metadata']['location'],
                                'start_date' => $data['metadata']['start_date'],
                            ],
                            'published_at' => now(),
                            'order' => $livewire->getOwnerRecord()->contents()->max('order') + 1,
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Event added')
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::FourExtraLarge),

                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalContent(fn (NewsletterContent $record) => view('filament.newsletter.content-preview', compact('record')))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function (NewsletterContent $record, RelationManager $livewire): void {
                        $newRecord = $record->replicate();
                        $newRecord->title = $record->title . ' (Copy)';
                        $newRecord->order = $livewire->getOwnerRecord()->contents()->max('order') + 1;
                        $newRecord->save();

                        Notification::make()
                            ->success()
                            ->title('Content duplicated')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('feature')
                        ->label('Mark as Featured')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['is_featured' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('unfeature')
                        ->label('Remove Featured')
                        ->icon('heroicon-o-star')
                        ->color('gray')
                        ->action(fn ($records) => $records->each->update(['is_featured' => false]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('reorder')
                        ->label('Auto-Reorder')
                        ->icon('heroicon-o-bars-3')
                        ->action(function ($records, RelationManager $livewire): void {
                            $records->each(function ($record, $index) {
                                $record->update(['order' => ($index + 1) * 10]);
                            });

                            Notification::make()
                                ->success()
                                ->title('Content reordered')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateHeading('No content added yet')
            ->emptyStateDescription('Start building your newsletter by adding stories, events, updates, or announcements.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add First Content')
                    ->modalWidth(MaxWidth::FourExtraLarge),
            ]);
    }
}
