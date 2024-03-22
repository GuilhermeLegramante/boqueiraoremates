<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use EightyNine\Reports\ReportsPlugin;
use Filament\Forms\Components\Select;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets;
use Hasnayeen\Themes\Http\Middleware\SetTheme;
use Hasnayeen\Themes\ThemesPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        Select::configureUsing(function (Select $select): void {
            $select
                ->preload()
                ->searchable();
        });

        TextColumn::configureUsing(function (TextColumn $textColumn): void {
            $textColumn
                ->sortable();
        });

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->brandName('Boqueirão Remates')
            ->sidebarCollapsibleOnDesktop()
            // ->brandLogo('https://boqueiraoremates.com/public/vendor/adminlte/dist/img/b.png')
            // ->brandLogoHeight(fn () => auth()->check() ? '3rem' : '6rem')
            ->favicon('https://boqueiraoremates.com/public/vendor/adminlte/dist/img/b.png')
            ->plugins([
                FilamentProgressbarPlugin::make()->color('#29b'),
                // ReportsPlugin::make(),
                // FilamentBackgroundsPlugin::make(),
                ThemesPlugin::make(),

            ])
            ->resources([
                config('filament-logger.activity_resource')
            ])
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
                SetTheme::class,

            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
