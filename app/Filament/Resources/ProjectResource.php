<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProjectResource extends Resource
{
  protected static ?string $model = Project::class;
  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
  protected static ?string $navigationGroup = 'Content';
  protected static ?int $navigationSort = 2;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make()
          ->schema([
            Forms\Components\TextInput::make('title')
              ->required()
              ->maxLength(255)
              ->live(onBlur: true)
              ->afterStateUpdated(fn (string $state, Forms\Set $set) =>
              $set('slug', str($state)->slug())
              ),

            Forms\Components\TextInput::make('slug')
              ->required()
              ->maxLength(255)
              ->unique(ignoreRecord: true)
              ->disabled(),

            Forms\Components\Textarea::make('description')
              ->required()
              ->rows(3)
              ->maxLength(65535),

            Forms\Components\RichEditor::make('content')
              ->required()
              ->columnSpanFull(),

            Forms\Components\SpatieMediaLibraryFileUpload::make('project_image')
              ->collection('project_image')
              ->image()
              ->imageEditor()
              ->imageEditorAspectRatios([
                '16:9',
                '4:3',
              ])
              ->maxSize(2048)
              ->helperText('Maximum file size: 2MB. Recommended resolution: 1920x1080px')
              ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
              ->columnSpanFull(),

            Forms\Components\SpatieMediaLibraryFileUpload::make('project_gallery')
              ->collection('project_gallery')
              ->multiple()
              ->image()
              ->imageEditor()
              ->maxSize(2048)
              ->helperText('Maximum file size: 2MB per image')
              ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
              ->maxFiles(6)
              ->columnSpanFull(),
          ])
          ->columnSpan(['lg' => 2]),

        Forms\Components\Section::make('Project Details')
          ->schema([
            Forms\Components\DatePicker::make('start_date')
              ->required(),

            Forms\Components\DatePicker::make('end_date'),

            Forms\Components\TextInput::make('funded_by')
              ->required()
              ->maxLength(255),

            Forms\Components\Select::make('status')
              ->options([
                'current' => 'Current',
                'completed' => 'Completed',
                'upcoming' => 'Upcoming',
              ])
              ->required(),

            Forms\Components\TextInput::make('people_reached')
              ->numeric()
              ->minValue(0),

            Forms\Components\TextInput::make('budget')
              ->numeric()
              ->prefix('$')
              ->maxValue(999999999999999.99),

            Forms\Components\Toggle::make('is_featured')
              ->default(false),

            Forms\Components\TextInput::make('order')
              ->numeric()
              ->default(0),

            Forms\Components\SpatieTagsInput::make('tags')
              ->type('project'),

            Forms\Components\Repeater::make('key_achievements')
              ->simple(
                Forms\Components\TextInput::make('achievement')
                  ->required()
              ),

            Forms\Components\KeyValue::make('meta_data')
              ->keyLabel('Field')
              ->valueLabel('Value')
              ->reorderable(),
          ])
          ->columnSpan(['lg' => 1]),
      ])
      ->columns(3);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\SpatieMediaLibraryImageColumn::make('project_image')
          ->collection('project_image')
          ->circular(),

        Tables\Columns\TextColumn::make('title')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('status')
          ->badge()
          ->color(fn (string $state): string => match ($state) {
            'current' => 'success',
            'completed' => 'gray',
            'upcoming' => 'warning',
          }),

        Tables\Columns\TextColumn::make('start_date')
          ->date()
          ->sortable(),

        Tables\Columns\IconColumn::make('is_featured')
          ->boolean(),

        Tables\Columns\TextColumn::make('order')
          ->sortable(),
      ])
      ->defaultSort('order')
      ->reorderable('order')
      ->filters([
        Tables\Filters\SelectFilter::make('status')
          ->options([
            'current' => 'Current',
            'completed' => 'Completed',
            'upcoming' => 'Upcoming',
          ]),
        Tables\Filters\TernaryFilter::make('is_featured'),
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
      'index' => Pages\ListProjects::route('/'),
      'create' => Pages\CreateProject::route('/create'),
      'edit' => Pages\EditProject::route('/{record}/edit'),
    ];
  }

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::count();
  }
}
