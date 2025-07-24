<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewUser extends ViewRecord
{
  protected static string $resource = UserResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\EditAction::make(),

      Actions\Action::make('manage_roles')
        ->label('Manage Roles')
        ->icon('heroicon-o-shield-check')
        ->color('warning')
        ->url(fn(): string => static::getResource()::getUrl('edit', ['record' => $this->record])),

      Actions\DeleteAction::make(),
    ];
  }

  public function infolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->schema([
        Infolists\Components\Section::make('User Information')
          ->schema([
            Infolists\Components\Split::make([
              Infolists\Components\Grid::make(2)
                ->schema([
                  Infolists\Components\TextEntry::make('name')
                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                    ->weight('bold'),

                  Infolists\Components\TextEntry::make('email')
                    ->icon('heroicon-o-envelope')
                    ->copyable(),

                  Infolists\Components\IconEntry::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                  Infolists\Components\IconEntry::make('email_verified_at')
                    ->label('Email Verified')
                    ->boolean()
                    ->getStateUsing(fn(User $record): bool => $record->email_verified_at !== null)
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor('warning'),

                  Infolists\Components\TextEntry::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->since(),

                  Infolists\Components\TextEntry::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->since(),
                ]),

              Infolists\Components\SpatieMediaLibraryImageEntry::make('avatar')
                ->collection('avatar')
                ->conversion('medium')
                ->circular()
                ->grow(false),
            ])->from('lg'),
          ])
          ->columns(2),

        Infolists\Components\Section::make('Roles & Permissions')
          ->schema([
            Infolists\Components\RepeatableEntry::make('roles')
              ->schema([
                Infolists\Components\TextEntry::make('name')
                  ->badge()
                  ->color(fn(string $state): string => match ($state) {
                    'super_admin' => 'danger',
                    'admin' => 'warning',
                    'content_manager' => 'warning',
                    'editor' => 'success',
                    'project_manager' => 'info',
                    'subscriber_manager' => 'purple',
                    'viewer' => 'slate',
                    default => 'gray',
                  }),
              ])
              ->columns(1)
              ->grid(4),

            Infolists\Components\TextEntry::make('all_permissions')
              ->label('All Permissions')
              ->getStateUsing(function (User $record): string {
                $permissions = $record->getAllPermissions();
                if ($permissions->isEmpty()) {
                  return 'No permissions assigned';
                }
                return $permissions->pluck('name')->sort()->implode(', ');
              })
              ->prose()
              ->columnSpanFull(),

            Infolists\Components\TextEntry::make('direct_permissions')
              ->label('Direct Permissions')
              ->getStateUsing(function (User $record): string {
                $permissions = $record->permissions;
                if ($permissions->isEmpty()) {
                  return 'No direct permissions assigned';
                }
                return $permissions->pluck('name')->sort()->implode(', ');
              })
              ->prose()
              ->columnSpanFull(),
          ])
          ->columns(2),

        Infolists\Components\Section::make('Activity Summary')
          ->schema([
            Infolists\Components\Grid::make(3)
              ->schema([
                Infolists\Components\TextEntry::make('posts_count')
                  ->label('Blog Posts')
                  ->getStateUsing(fn(User $record): int => $record->posts()->count())
                  ->icon('heroicon-o-document-text'),

                Infolists\Components\TextEntry::make('total_likes')
                  ->label('Total Likes Received')
                  ->getStateUsing(fn(User $record): int => $record->totalPostLikes())
                  ->icon('heroicon-o-heart'),

                Infolists\Components\TextEntry::make('comments_count')
                  ->label('Comments Made')
                  ->getStateUsing(fn(User $record): int => $record->comments()->count() ?? 0)
                  ->icon('heroicon-o-chat-bubble-left'),
              ]),
          ])
          ->collapsible(),

        Infolists\Components\Section::make('Additional Information')
          ->schema([
            Infolists\Components\KeyValueEntry::make('metadata')
              ->columnSpanFull(),
          ])
          ->collapsible()
          ->collapsed(),
      ]);
  }
}
