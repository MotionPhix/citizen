<?php

namespace App\Filament\Resources\NewsletterIssueResource\RelationManagers;

use App\Models\NewsletterContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ContentsRelationManager extends RelationManager
{
    protected static string $relationship = 'contents';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $title = 'Newsletter Content';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options(NewsletterContent::getTypes())
                    ->required()
                    ->reactive(),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('excerpt')
                    ->maxLength(500)
                    ->rows(2)
                    ->visible(fn ($get) => in_array($get('type'), ['story', 'update', 'announcement']))
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('content')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'link',
                        'bulletList',
                        'orderedList',
                        'h2',
                        'h3',
                    ])
                    ->columnSpanFull(),

                // Event-specific fields
                Forms\Components\Section::make('Event Details')
                    ->schema([
                        Forms\Components\TextInput::make('metadata.location')
                            ->label('Location'),
                        Forms\Components\DateTimePicker::make('metadata.start_date')
                            ->label('Start Date'),
                        Forms\Components\DateTimePicker::make('metadata.end_date')
                            ->label('End Date'),
                        Forms\Components\TextInput::make('metadata.registration_url')
                            ->label('Registration URL')
                            ->url()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => $get('type') === 'event')
                    ->collapsible(),

                // Update-specific fields
                Forms\Components\Select::make('category')
                    ->options(NewsletterContent::getCategories())
                    ->visible(fn ($get) => in_array($get('type'), ['update', 'announcement'])),

                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('images')
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('url')
                    ->url()
                    ->columnSpanFull(),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured'),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->default(now()),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'story',
                        'success' => 'event',
                        'warning' => 'update',
                        'danger' => 'announcement',
                    ]),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\ImageColumn::make('image'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),

                Tables\Columns\TextColumn::make('order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(NewsletterContent::getTypes()),
                Tables\Filters\TernaryFilter::make('is_featured'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Content'),
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
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('feature')
                        ->label('Mark as Featured')
                        ->action(fn ($records) => $records->each->update(['is_featured' => true])),
                ]),
            ]);
    }
}
