<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
  public function panel(Panel $panel): Panel
  {
    return $panel
      ->default()
      ->id('admin')
      ->path('admin')
      ->login()
      ->colors([
        'primary' => Color::Blue,
      ])
      ->font('Geist Mono')
      ->brandLogo(asset('images/logo.png'))
      ->brandLogoHeight('2.5rem')
      ->brandName('Citizen Alliance')
      ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
      ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
      ->sidebarCollapsibleOnDesktop()
      ->topNavigation()
      ->maxContentWidth(MaxWidth::FiveExtraLarge)
      ->userMenuItems([
        'profile' => MenuItem::make()
          ->icon('heroicon-o-user')
          ->url(fn () => route('filament.admin.resources.users.edit', auth()->user()->id)),

        'logout' => MenuItem::make()->label('Log out')
          ->icon('heroicon-o-arrow-right-on-rectangle')
          ->url(fn () => route('filament.admin.auth.logout'))
          ->color('danger'),
      ])
      ->pages([])
      ->databaseNotifications()
      ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
      ->widgets([])
      ->middleware([
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        SubstituteBindings::class,
        DisableBladeIconComponents::class,
        DispatchServingFilamentEvent::class,
      ])
      ->authMiddleware([
        Authenticate::class,
      ]);
  }
}
