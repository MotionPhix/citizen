<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterContentResource\Pages;
use App\Models\NewsletterContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class NewsletterContentResource extends Resource
{
    protected static ?string $model = NewsletterContent::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Newsletter';
    protected static ?string $navigationLabel = 'Content';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('newsletter_issue_id')
                            ->relationship('newsletterIssue', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('title')
                                    ->required(),
                                Forms\Components\Textarea::make('description'),
                            ]),

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
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Content')
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
                    ->visible(fn ($get) => $get('type') === 'event'),

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
                    ->visible(fn ($get) => $get('type') === 'story'),

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
                    ->visible(fn ($get) => in_array($get('type'), ['update', 'announcement'])),

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
                    ->toggleable(),

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
                    ->limit(50),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\ImageColumn::make('image')
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
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (NewsletterContent $record) {
                        $newRecord = $record->replicate();
                        $newRecord->title = $record->title . ' (Copy)';
                        $newRecord->save();

                        return redirect()->route('filament.admin.resources.newsletter-contents.edit', $newRecord);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('feature')
                        ->label('Mark as Featured')
                        ->icon('heroicon-o-star')
                        ->action(fn ($records) => $records->each->update(['is_featured' => true])),
                    Tables\Actions\BulkAction::make('unfeature')
                        ->label('Remove Featured')
                        ->icon('heroicon-o-star')
                        ->action(fn ($records) => $records->each->update(['is_featured' => false])),
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
        return static::getModel()::count();
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['newsletterIssue']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'excerpt', 'content', 'newsletterIssue.title'];
    }
}
