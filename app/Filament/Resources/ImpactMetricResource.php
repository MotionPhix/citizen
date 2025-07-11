<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImpactMetricResource\Pages;
use App\Models\ImpactMetric;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;

class ImpactMetricResource extends Resource
{
  protected static ?string $model = ImpactMetric::class;
  protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
  protected static ?string $navigationGroup = 'Content';
  protected static ?string $navigationLabel = 'Metrics';
  protected static ?int $navigationSort = 3;

  public static function form(Forms\Form $form): Forms\Form
  {
    return $form
      ->schema([
        Forms\Components\Select::make('icon')
          ->required()
          ->options([
            'users' => 'Users',
            'handshake' => 'Handshake',
            'medical' => 'Medical',
            'water' => 'Water',
            'training' => 'Training',
            'women' => 'Women',
            'volunteers' => 'Volunteers',
          ])
          ->helperText('Select an icon for this metric'),

        Forms\Components\TextInput::make('title')
          ->required()
          ->maxLength(255),

        Forms\Components\TextInput::make('metric')
          ->required()
          ->numeric()
          ->mask(RawJs::make('$money($input, ",")'))
          ->stripCharacters(',')
          ->helperText('Enter the numeric value for this metric, e.g., 55000'),

        Forms\Components\TextInput::make('sort_order')
          ->numeric()
          ->default(0),

        Forms\Components\Textarea::make('description')
          ->required()
          ->maxLength(500)
          ->columnSpan('full'),

        Forms\Components\Toggle::make('is_published')
          ->default(true),
      ]);
  }

  public static function table(Tables\Table $table): Tables\Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('title')
          ->label('Metric Title')
          ->description(
            fn(ImpactMetric $record): string => $record->metric,
            position: 'above'
          )
          ->searchable([
            'title',
            'metric',
          ]),

        Tables\Columns\IconColumn::make('is_published')
          ->alignCenter()
          ->boolean(),

        Tables\Columns\TextColumn::make('sort_order')
          ->label('Order')
          ->numeric()
          ->alignCenter()
          ->sortable(),

        Tables\Columns\TextColumn::make('updated_at')
          ->label('Last Updated')
          ->since()
          ->dateTimeTooltip()
          ->sortable(),
      ])
      ->defaultSort('sort_order')
      ->reorderable('sort_order')
      ->filters([
        Tables\Filters\TernaryFilter::make('is_published'),
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\EditAction::make(),
          Tables\Actions\DeleteAction::make(),
        ])
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
      'index' => Pages\ListImpactMetrics::route('/'),
      'create' => Pages\CreateImpactMetric::route('/create'),
      'edit' => Pages\EditImpactMetric::route('/{record}/edit'),
    ];
  }

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::count();
  }
}
