<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Nnjeim\World\World;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
  protected static ?string $model = User::class;
  protected static ?string $navigationIcon = 'heroicon-o-users';
  protected static ?string $navigationGroup = 'System';
  protected static ?int $navigationSort = 7;

  public static function form(Form $form): Form
  {
    $countries = World::countries()
      ->data
      ->pluck('name', 'iso2')
      ->toArray();

    return $form
      ->schema([
        Forms\Components\Section::make()
          ->schema([
            Forms\Components\TextInput::make('name')
              ->required()
              ->maxLength(255),

            Forms\Components\TextInput::make('email')
              ->email()
              ->required()
              ->maxLength(255)
              ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('password')
              ->password()
              ->dehydrateStateUsing(fn($state) => Hash::make($state))
              ->dehydrated(fn($state) => filled($state))
              ->required(fn(string $context): bool => $context === 'create'),

            Forms\Components\Select::make('roles.name')
              ->label('Role')
              ->relationship('roles', 'name')
              ->preload()
              ->native(false),

            Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
              ->collection('avatar')
              ->image()
              ->imageEditor()
              ->circleCropper()
              ->columnSpanFull(),

            Forms\Components\Toggle::make('is_active')
              ->label('Active')
              ->default(true),
          ])
          ->columns(2),

        Forms\Components\Section::make('Metadata')
          ->description('Additional user information')
          ->schema([
            Forms\Components\TextInput::make('metadata.phone')
              ->label('Phone Number')
              ->tel()
              ->maxLength(20),

            Forms\Components\TextInput::make('metadata.position')
              ->label('Job Position')
              ->maxLength(100),

            Forms\Components\Select::make('metadata.department')
              ->label('Department')
              ->options([
                'engineering' => 'Engineering',
                'marketing' => 'Marketing',
                'sales' => 'Sales',
                'finance' => 'Finance',
                'hr' => 'Human Resources',
                'other' => 'Other',
              ]),

            Forms\Components\DatePicker::make('metadata.joined_date')
              ->label('Join Date')
              ->default(now()),

            Forms\Components\Textarea::make('metadata.bio')
              ->label('Biography')
              ->columnSpanFull()
              ->rows(3),

            Forms\Components\Section::make('Social Media')
              ->schema([
                Forms\Components\TextInput::make('metadata.social.linkedin')
                  ->label('LinkedIn')
                  ->prefix('https://linkedin.com/in/')
                  ->maxLength(100),

                Forms\Components\TextInput::make('metadata.social.twitter')
                  ->label('Twitter')
                  ->prefix('@')
                  ->maxLength(100),

                Forms\Components\TextInput::make('metadata.social.github')
                  ->label('GitHub')
                  ->prefix('https://github.com/')
                  ->maxLength(100),
              ])
              ->columns(3),

            Forms\Components\Group::make()
              ->schema([
                Forms\Components\TextInput::make('metadata.address.street')
                  ->label('Street Address'),

                Forms\Components\TextInput::make('metadata.address.city')
                  ->label('City'),

                Forms\Components\TextInput::make('metadata.address.state')
                  ->label('State/Province'),

                Forms\Components\TextInput::make('metadata.address.postal_code')
                  ->label('Postal Code'),

                Forms\Components\Select::make('metadata.address.country')
                  ->label('Country')
                  ->searchable()
                  ->options($countries),
              ])
              ->columns(2),
          ])
          ->collapsible(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
          ->collection('avatar')
          ->circular(),

        Tables\Columns\TextColumn::make('name')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('email')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('roles.name')
          ->badge()
          ->formatStateUsing(fn(string $state) => str($state)->title())
          ->colors([
            'danger' => 'super-admin',
            'warning' => 'admin',
            'success' => 'editor',
            'primary' => 'user',
          ]),


        Tables\Columns\TextColumn::make('is_active')
          ->label('Status')
          ->badge()
          ->formatStateUsing(fn(bool $state) => $state ? 'Active' : 'Inactive')
          ->color(fn(bool $state): string => match ($state) {
            true => 'success',
            false => 'danger',
          })
          ->sortable(),

        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),

        Tables\Columns\TextColumn::make('updated_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('role')
          ->options(fn() => Role::pluck('name', 'name')),

        Tables\Filters\TernaryFilter::make('is_active')
          ->label('Active'),
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\EditAction::make(),
          Tables\Actions\DeleteAction::make()
            ->before(function (User $record) {
              if ($record->hasRole('super-admin') && User::role('super-admin')->count() <= 1) {
                throw new \Exception('Cannot delete the last super administrator.');
              }
            }),
        ])
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make()
            ->before(function (User $record) {
              if ($record->hasRole('super-admin') && User::role('super-admin')->count() <= 1) {
                Notification::make()
                  ->title('Cannot delete the last Super Admin')
                  ->danger()
                  ->send();
              }
            }),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      'edit' => Pages\EditUser::route('/{record}/edit'),
      'view' => Pages\ViewUser::route('/{record}'),
    ];
  }

  public static function getGlobalSearchResultDetails(Model $record): array
  {
    return [
      'Role' => str($record->role)->title(),
    ];
  }

  protected function mutateFormDataBeforeSave(array $data): array
  {
    // Ensure we're working with a single role
    if (isset($data['role'])) {
      $this->record?->roles()->detach();
      $this->record?->assignRole($data['role']);
    }

    return $data;
  }

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::count();
  }
}
