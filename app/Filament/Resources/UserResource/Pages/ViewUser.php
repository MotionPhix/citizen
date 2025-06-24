<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;

class ViewUser extends ViewRecord
{
  protected static string $resource = UserResource::class;

  public function infolist(Infolists\Infolist $infolist): Infolists\Infolist
  {
    return $infolist
      ->schema([
        Infolists\Components\Section::make('User Details')
          ->schema([
            Infolists\Components\SpatieMediaLibraryImageEntry::make('avatar')
              ->collection('avatar')
              ->label('Profile Picture')
              ->extraAttributes([
                'alt' => 'User profile picture',
                'loading' => 'lazy',
              ])
              ->columnSpan(2),

            Infolists\Components\TextEntry::make('name')
              ->label('Full Name')
              ->weight('bold')
              ->formatStateUsing(fn(string $state) => str($state)->title()),

            Infolists\Components\TextEntry::make('email')
              ->label('Email Address')
              ->weight('bold')
              ->formatStateUsing(fn(string $state) => str($state)->lower()),

            Infolists\Components\TextEntry::make('roles.name')
              ->label('Role')
              ->badge()
              ->formatStateUsing(fn(string $state) => str($state)->title())
              ->color(fn($state) => match ($state) {
                'super-admin' => 'danger',
                'admin' => 'warning',
                'editor' => 'success',
                default => 'primary',
              }),

            Infolists\Components\TextEntry::make('is_active')
              ->label('Status')
              ->badge()
              ->formatStateUsing(fn(bool $state) => $state ? 'Active' : 'Inactive')
              ->color(fn($state) => $state ? 'success' : 'danger'),

            Infolists\Components\TextEntry::make('created_at')
              ->dateTime()
              ->label('Created At'),

            Infolists\Components\TextEntry::make('updated_at')
              ->dateTime()
              ->label('Last Modified'),
          ])
          ->columns(2),

        Infolists\Components\Section::make('Contact & Position')
          ->schema([
            Infolists\Components\TextEntry::make('metadata.phone')
              ->label('Phone Number')
              ->icon('heroicon-m-phone'),

            Infolists\Components\TextEntry::make('metadata.position')
              ->label('Job Position')
              ->icon('heroicon-m-briefcase'),

            Infolists\Components\TextEntry::make('metadata.department')
              ->label('Department')
              ->badge()
              ->formatStateUsing(fn($state) => str($state)->title())
              ->icon('heroicon-m-building-office'),

            Infolists\Components\TextEntry::make('metadata.joined_date')
              ->label('Join Date')
              ->date()
              ->icon('heroicon-m-calendar'),
          ])
          ->columns(2),

        Infolists\Components\Section::make('Biography')
          ->schema([
            Infolists\Components\TextEntry::make('metadata.bio')
              ->markdown()
              ->columnSpanFull(),
          ]),

        Infolists\Components\Section::make('Social Media')
          ->schema([
            Infolists\Components\TextEntry::make('metadata.social.linkedin')
              ->label('LinkedIn')
              ->url(fn($state) => "https://linkedin.com/in/{$state}")
              ->icon('heroicon-m-link')
              ->openUrlInNewTab(),

            Infolists\Components\TextEntry::make('metadata.social.twitter')
              ->label('Twitter')
              ->url(fn($state) => "https://twitter.com/{$state}")
              ->formatStateUsing(fn($state) => '@' . $state)
              ->icon('heroicon-m-link')
              ->openUrlInNewTab(),

            Infolists\Components\TextEntry::make('metadata.social.github')
              ->label('GitHub')
              ->url(fn($state) => "https://github.com/{$state}")
              ->icon('heroicon-m-link')
              ->openUrlInNewTab(),
          ])
          ->columns(3),

        Infolists\Components\Section::make('Address')
          ->schema([
            Infolists\Components\TextEntry::make('metadata.address.street')
              ->label('Street Address'),

            Infolists\Components\TextEntry::make('metadata.address.city')
              ->label('City'),

            Infolists\Components\TextEntry::make('metadata.address.state')
              ->label('State/Province'),

            Infolists\Components\TextEntry::make('metadata.address.postal_code')
              ->label('Postal Code'),

            Infolists\Components\TextEntry::make('metadata.address.country')
              ->label('Country'),
          ])
          ->columns(2),

        Infolists\Components\Section::make('System Information')
          ->schema([
            Infolists\Components\TextEntry::make('created_at')
              ->dateTime()
              ->label('Created At')
              ->icon('heroicon-m-clock'),

            Infolists\Components\TextEntry::make('updated_at')
              ->dateTime()
              ->label('Last Modified')
              ->icon('heroicon-m-clock'),
          ])
          ->columns(2),

        Infolists\Components\Section::make('Role Permissions')
          ->description('Permissions granted through the assigned role')
          ->schema([
            Infolists\Components\RepeatableEntry::make('roles')
              ->hiddenLabel()
              ->schema([
                Infolists\Components\RepeatableEntry::make('permissions')
                  ->schema([
                    Infolists\Components\TextEntry::make('name')
                      ->hiddenLabel()
                      ->formatStateUsing(fn(string $state) => str($state)->title())
                      ->color('info'),
                  ])
                  ->grid(3),
              ]),
          ])
          ->collapsible(),
      ]);
  }

  protected function getHeaderActions(): array
  {
    return [
      Actions\EditAction::make(),
    ];
  }

  public function getTitle(): \Illuminate\Contracts\Support\Htmlable|string
  {
    return $this->record->name . '`s Details';
  }

  public function getMaxContentWidth(): \Filament\Support\Enums\MaxWidth
  {
    return \Filament\Support\Enums\MaxWidth::FourExtraLarge;
  }
}
