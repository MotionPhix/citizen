<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
  protected static ?string $model = User::class;
  protected static ?string $navigationIcon = 'heroicon-o-users';
  protected static ?string $navigationGroup = 'System';
  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
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

            Forms\Components\Select::make('role')
              ->options([
                'admin' => 'Administrator',
                'user' => 'Regular User',
              ])
              ->required()
              ->native(false),

            SpatieMediaLibraryFileUpload::make('avatar')
              ->collection('avatar')
              ->image()
              ->imageEditor()
              ->circleCropper()
              ->columnSpanFull(),

            Forms\Components\DateTimePicker::make('email_verified_at')
              ->label('Email Verified At'),

            Forms\Components\Toggle::make('is_active')
              ->label('Active')
              ->default(true),
          ])
          ->columns(2),

        Forms\Components\Section::make('Metadata')
          ->schema([
            Forms\Components\Placeholder::make('created_at')
              ->label('Created at')
              ->content(fn(?User $record): string => $record?->created_at?->diffForHumans() ?? '-'),

            Forms\Components\Placeholder::make('updated_at')
              ->label('Last modified at')
              ->content(fn(?User $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
          ])
          ->columns(2)
          ->collapsed(),
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

        Tables\Columns\BadgeColumn::make('role')
          ->colors([
            'danger' => 'admin',
            'primary' => 'user',
          ]),

        Tables\Columns\IconColumn::make('email_verified_at')
          ->label('Verified')
          ->boolean()
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
          ->options([
            'admin' => 'Administrator',
            'user' => 'Regular User',
          ]),

        Tables\Filters\TernaryFilter::make('email_verified_at')
          ->label('Email verified')
          ->nullable(),
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\ViewAction::make(),
          Tables\Actions\EditAction::make(),
          Tables\Actions\Action::make('impersonate')
            ->label('Login as user')
            ->icon('heroicon-o-identification')
            ->color('warning')
            ->requiresConfirmation()
            ->action(function (User $user) {
              // Add your impersonation logic here if needed
            })
            ->visible(fn (User $user): bool => auth()->user()->isAdmin() && auth()->id() !== $user->id),
        ]),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make()
            ->action(function () {
              // Prevent deleting the last admin user
              $adminCount = User::where('role', 'admin')->count();
              if ($adminCount <= 1) {
                throw new \Exception('Cannot delete the last administrator.');
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

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::count();
  }
}
