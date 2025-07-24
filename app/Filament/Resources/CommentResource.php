<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;

class CommentResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Comment::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Comment Details')
                    ->schema([
                        Forms\Components\Select::make('blog_id')
                            ->relationship('blog', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('parent_id')
                            ->label('Reply to Comment')
                            ->relationship('parent', 'content')
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn (Comment $record): string =>
                                "{$record->display_name}: " . \Illuminate\Support\Str::limit($record->content, 50)
                            ),

                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->rows(4)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Author Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Leave empty for anonymous comment'),

                        Forms\Components\TextInput::make('author_name')
                            ->label('Name (for anonymous)')
                            ->maxLength(100)
                            ->hidden(fn (Forms\Get $get): bool => !empty($get('user_id'))),

                        Forms\Components\TextInput::make('author_email')
                            ->label('Email (for anonymous)')
                            ->email()
                            ->maxLength(255)
                            ->hidden(fn (Forms\Get $get): bool => !empty($get('user_id'))),

                        Forms\Components\TextInput::make('author_website')
                            ->label('Website (for anonymous)')
                            ->url()
                            ->maxLength(255)
                            ->hidden(fn (Forms\Get $get): bool => !empty($get('user_id'))),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Moderation')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(Comment::getStatuses())
                            ->required()
                            ->default(Comment::STATUS_PENDING),

                        Forms\Components\DateTimePicker::make('approved_at')
                            ->label('Approved At')
                            ->hidden(fn (Forms\Get $get): bool => $get('status') !== Comment::STATUS_APPROVED),

                        Forms\Components\Select::make('approved_by')
                            ->label('Approved By')
                            ->relationship('approver', 'name')
                            ->hidden(fn (Forms\Get $get): bool => $get('status') !== Comment::STATUS_APPROVED),

                        Forms\Components\Toggle::make('notify_on_reply')
                            ->label('Notify on Reply')
                            ->default(true),

                        Forms\Components\TextInput::make('spam_score')
                            ->label('Spam Score')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->maxValue(1)
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Technical Details')
                    ->schema([
                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Address')
                            ->disabled(),

                        Forms\Components\Textarea::make('user_agent')
                            ->label('User Agent')
                            ->rows(2)
                            ->disabled(),
                    ])
                    ->columns(1)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('blog.title')
                    ->label('Blog Post')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('display_name')
                    ->label('Author')
                    ->searchable(['author_name', 'author_email'])
                    ->sortable()
                    ->weight(FontWeight::Medium),

                Tables\Columns\TextColumn::make('content')
                    ->label('Comment')
                    ->limit(60)
                    ->tooltip(function (Comment $record): string {
                        return $record->content;
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => Comment::STATUS_PENDING,
                        'success' => Comment::STATUS_APPROVED,
                        'danger' => Comment::STATUS_SPAM,
                        'gray' => Comment::STATUS_TRASH,
                    ]),

                Tables\Columns\IconColumn::make('is_anonymous')
                    ->label('Anonymous')
                    ->boolean()
                    ->getStateUsing(fn (Comment $record): bool => $record->is_anonymous),

                Tables\Columns\TextColumn::make('spam_score')
                    ->label('Spam Score')
                    ->numeric(2)
                    ->color(fn (float $state): string => match (true) {
                        $state >= 0.7 => 'danger',
                        $state >= 0.4 => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Posted')
                    ->dateTime()
                    ->sortable()
                    ->since(),

                Tables\Columns\TextColumn::make('replies_count')
                    ->label('Replies')
                    ->counts('allReplies')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(Comment::getStatuses())
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_anonymous')
                    ->label('Anonymous Comments')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNull('user_id'),
                        false: fn (Builder $query) => $query->whereNotNull('user_id'),
                    ),

                Tables\Filters\Filter::make('spam_score')
                    ->form([
                        Forms\Components\TextInput::make('min_spam_score')
                            ->label('Minimum Spam Score')
                            ->numeric()
                            ->step(0.1),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_spam_score'],
                                fn (Builder $query, $score): Builder => $query->where('spam_score', '>=', $score),
                            );
                    }),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Comment $record) => $record->approve())
                    ->visible(fn (Comment $record): bool => $record->status !== Comment::STATUS_APPROVED)
                    ->requiresConfirmation(),

                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (Comment $record) => $record->reject())
                    ->visible(fn (Comment $record): bool => $record->status === Comment::STATUS_PENDING)
                    ->requiresConfirmation(),

                Tables\Actions\Action::make('mark_spam')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->action(fn (Comment $record) => $record->markAsSpam())
                    ->visible(fn (Comment $record): bool => $record->status !== Comment::STATUS_SPAM)
                    ->requiresConfirmation(),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->approve())
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->reject())
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('mark_spam')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->markAsSpam())
                        ->requiresConfirmation(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', Comment::STATUS_PENDING)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $pendingCount = static::getModel()::where('status', Comment::STATUS_PENDING)->count();
        return $pendingCount > 0 ? 'warning' : null;
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
