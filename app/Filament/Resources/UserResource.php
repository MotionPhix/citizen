<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Support\Enums\MaxWidth;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Users';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
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
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->helperText('Leave empty to keep current password when editing'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive users cannot access the system'),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->helperText('Set to mark email as verified'),
                    ])
                    ->columns(2)
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Section::make('Roles & Permissions')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->optionsLimit(50)
                            ->helperText('Assign roles to this user. Roles define what the user can access.')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('permissions')
                            ->relationship('permissions', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->optionsLimit(100)
                            ->helperText('Grant specific permissions to this user (in addition to role permissions)')
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('role_info')
                            ->label('Role Information')
                            ->content(function (?User $record): string {
                                if (!$record) {
                                    return 'Save the user to see role information';
                                }

                                $roles = $record->roles;
                                if ($roles->isEmpty()) {
                                    return 'No roles assigned';
                                }

                                $roleInfo = [];
                                foreach ($roles as $role) {
                                    $permissionCount = $role->permissions()->count();
                                    $roleInfo[] = "{$role->name} ({$permissionCount} permissions)";
                                }

                                return implode(' • ', $roleInfo);
                            })
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(['lg' => 1]),

                Forms\Components\Section::make('Profile')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                            ->collection('avatar')
                            ->image()
                            ->imageEditor()
                            ->circleCropper()
                            ->maxSize(2048)
                            ->helperText('Upload a profile picture (max 2MB)')
                            ->columnSpanFull(),

                        Forms\Components\KeyValue::make('metadata')
                            ->keyLabel('Field')
                            ->valueLabel('Value')
                            ->reorderable()
                            ->helperText('Additional user information')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(['lg' => 2])
                    ->collapsible(),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('avatar')
                    ->conversion('thumb')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (User $record): string => $record->email),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin' => 'warning',
                        'content_manager' => 'success',
                        'editor' => 'info',
                        'project_manager' => 'purple',
                        'subscriber_manager' => 'orange',
                        'viewer' => 'gray',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->getStateUsing(fn (User $record): bool => $record->email_verified_at !== null)
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All users')
                    ->trueLabel('Active users')
                    ->falseLabel('Inactive users'),

                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email Verification')
                    ->placeholder('All users')
                    ->trueLabel('Verified emails')
                    ->falseLabel('Unverified emails')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('email_verified_at'),
                        false: fn (Builder $query) => $query->whereNull('email_verified_at'),
                    ),

                Tables\Filters\Filter::make('recent_users')
                    ->label('Recent (Last 30 days)')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30)))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation(),
                ])
                    ->label('Actions')
                    ->icon('heroicon-o-ellipsis-vertical'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No users found')
            ->emptyStateDescription('Create your first user to get started with user management.')
            ->emptyStateIcon('heroicon-o-users');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['roles']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'roles.name'];
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
