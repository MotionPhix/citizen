<?php

namespace App\Filament\Resources;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;
use App\Filament\Resources\RoleResource\Pages;

class RoleResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Roles & Permissions';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Role Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Role name should be lowercase with hyphens (e.g., content-manager)'),

                        Forms\Components\TextInput::make('guard_name')
                            ->default('web')
                            ->required()
                            ->helperText('Guard name for this role (usually "web")'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Permissions')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->relationship('permissions', 'name')
                            ->searchable()
                            ->bulkToggleable()
                            ->gridDirection('row')
                            ->columns(3)
                            ->options(function () {
                                return Utils::getResourcePermissionOptions();
                            })
                            ->descriptions(function () {
                                return [
                                    // Resource permissions
                                    'view_any_blog' => 'View all blog posts',
                                    'view_blog' => 'View individual blog posts',
                                    'create_blog' => 'Create new blog posts',
                                    'update_blog' => 'Edit blog posts',
                                    'delete_blog' => 'Delete blog posts',
                                    'delete_any_blog' => 'Delete any blog posts',
                                    'force_delete_blog' => 'Permanently delete blog posts',
                                    'force_delete_any_blog' => 'Permanently delete any blog posts',
                                    'restore_blog' => 'Restore deleted blog posts',
                                    'restore_any_blog' => 'Restore any deleted blog posts',
                                    'replicate_blog' => 'Duplicate blog posts',
                                    'reorder_blog' => 'Reorder blog posts',

                                    // User permissions
                                    'view_any_user' => 'View all users',
                                    'view_user' => 'View individual users',
                                    'create_user' => 'Create new users',
                                    'update_user' => 'Edit users',
                                    'delete_user' => 'Delete users',
                                    'delete_any_user' => 'Delete any users',
                                    'force_delete_user' => 'Permanently delete users',
                                    'force_delete_any_user' => 'Permanently delete any users',
                                    'restore_user' => 'Restore deleted users',
                                    'restore_any_user' => 'Restore any deleted users',
                                    'replicate_user' => 'Duplicate users',
                                    'reorder_user' => 'Reorder users',

                                    // Role permissions
                                    'view_any_role' => 'View all roles',
                                    'view_role' => 'View individual roles',
                                    'create_role' => 'Create new roles',
                                    'update_role' => 'Edit roles',
                                    'delete_role' => 'Delete roles',
                                    'delete_any_role' => 'Delete any roles',

                                    // Project permissions
                                    'view_any_project' => 'View all projects',
                                    'view_project' => 'View individual projects',
                                    'create_project' => 'Create new projects',
                                    'update_project' => 'Edit projects',
                                    'delete_project' => 'Delete projects',
                                    'delete_any_project' => 'Delete any projects',

                                    // Comment permissions
                                    'view_any_comment' => 'View all comments',
                                    'view_comment' => 'View individual comments',
                                    'create_comment' => 'Create new comments',
                                    'update_comment' => 'Edit comments',
                                    'delete_comment' => 'Delete comments',
                                    'delete_any_comment' => 'Delete any comments',

                                    // Newsletter permissions
                                    'view_any_newsletter::content' => 'View all newsletter content',
                                    'view_newsletter::content' => 'View individual newsletter content',
                                    'create_newsletter::content' => 'Create newsletter content',
                                    'update_newsletter::content' => 'Edit newsletter content',
                                    'delete_newsletter::content' => 'Delete newsletter content',
                                    'delete_any_newsletter::content' => 'Delete any newsletter content',

                                    'view_any_newsletter::issue' => 'View all newsletter issues',
                                    'view_newsletter::issue' => 'View individual newsletter issues',
                                    'create_newsletter::issue' => 'Create newsletter issues',
                                    'update_newsletter::issue' => 'Edit newsletter issues',
                                    'delete_newsletter::issue' => 'Delete newsletter issues',
                                    'delete_any_newsletter::issue' => 'Delete any newsletter issues',

                                    // Subscriber permissions
                                    'view_any_subscriber' => 'View all subscribers',
                                    'view_subscriber' => 'View individual subscribers',
                                    'create_subscriber' => 'Create new subscribers',
                                    'update_subscriber' => 'Edit subscribers',
                                    'delete_subscriber' => 'Delete subscribers',
                                    'delete_any_subscriber' => 'Delete any subscribers',

                                    // Impact Metric permissions
                                    'view_any_impact::metric' => 'View all impact metrics',
                                    'view_impact::metric' => 'View individual impact metrics',
                                    'create_impact::metric' => 'Create impact metrics',
                                    'update_impact::metric' => 'Edit impact metrics',
                                    'delete_impact::metric' => 'Delete impact metrics',
                                    'delete_any_impact::metric' => 'Delete any impact metrics',

                                    // Contact Submission permissions
                                    'view_any_contact::submission' => 'View all contact submissions',
                                    'view_contact::submission' => 'View individual contact submissions',
                                    'create_contact::submission' => 'Create contact submissions',
                                    'update_contact::submission' => 'Edit contact submissions',
                                    'delete_contact::submission' => 'Delete contact submissions',
                                    'delete_any_contact::submission' => 'Delete any contact submissions',

                                    // Widget permissions
                                    'widget_BlogOverviewStats' => 'View blog overview statistics',
                                    'widget_EngagementStats' => 'View engagement statistics',
                                    'widget_NewsletterIssueStats' => 'View newsletter statistics',
                                    'widget_ProjectOverviewStats' => 'View project statistics',
                                    'widget_SubscriberStats' => 'View subscriber statistics',
                                ];
                            })
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Role Summary')
                    ->schema([
                        Forms\Components\Placeholder::make('users_count')
                            ->label('Users with this role')
                            ->content(function (?Role $record): string {
                                if (!$record) {
                                    return 'Save the role to see user count';
                                }
                                $count = $record->users()->count();
                                return $count . ' user' . ($count !== 1 ? 's' : '');
                            }),

                        Forms\Components\Placeholder::make('permissions_count')
                            ->label('Total permissions')
                            ->content(function (?Role $record): string {
                                if (!$record) {
                                    return 'Save the role to see permission count';
                                }
                                $count = $record->permissions()->count();
                                return $count . ' permission' . ($count !== 1 ? 's' : '');
                            }),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin' => 'warning',
                        'content_manager' => 'success',
                        'editor' => 'info',
                        'project_manager' => 'purple',
                        'subscriber_manager' => 'orange',
                        'viewer' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('guard_name')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

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
                Tables\Filters\Filter::make('has_users')
                    ->label('Has Users')
                    ->query(fn(Builder $query): Builder => $query->has('users'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->action(function (Role $record) {
                        if ($record->users()->count() > 0) {
                            throw new \Exception('Cannot delete role that has users assigned to it.');
                        }
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('No roles found')
            ->emptyStateDescription('Create your first role to start managing permissions.')
            ->emptyStateIcon('heroicon-o-shield-check');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
