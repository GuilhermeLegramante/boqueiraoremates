<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use App\Filament\Pages\CommissionReport;
use App\Filament\Resources\ClientResource\Widgets\ClientRegisterOriginChart;
use App\Filament\Resources\ClientResource\Widgets\ClientSituationChart;
use App\Filament\Resources\ClientResource\Widgets\StatsOverview;
use App\Filament\Resources\ClientResource\Widgets\VersionWidget;
use App\Filament\Widgets\CommissionPerMonthChart;
use App\Filament\Widgets\OrdersPerMonthChart;
use EightyNine\Reports\ReportsPlugin;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
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

        Table::configureUsing(function (Table $table) {
            $table->paginationPageOptions([10, 25, 50, 100]); // Não inclui -1, que é o "Ver todos"
        });

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile(EditProfile::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->passwordReset()
            ->registration(Register::class)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->resources([
                // CommissionReport::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                VersionWidget::class,
                StatsOverview::class,
                ClientSituationChart::class,
                ClientRegisterOriginChart::class,
                OrdersPerMonthChart::class,
                CommissionPerMonthChart::class
            ])
            ->brandName('Boqueirão Remates')
            ->sidebarCollapsibleOnDesktop()
            ->brandLogo(asset('img/logo.png'))
            ->brandLogoHeight(fn() => auth()->check() ? '3rem' : '6rem')
            ->favicon(asset('img/logo.png'))
            ->plugins([
                // FilamentProgressbarPlugin::make()->color('#29b'),
                // ReportsPlugin::make(),
                // FilamentBackgroundsPlugin::make(),
                ThemesPlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ])
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Relatórios'),
                NavigationGroup::make()
                    ->label('Controle de Acesso'),
                NavigationGroup::make()
                    ->label('Configurações'),
                NavigationGroup::make()
                    ->label('Parâmetros')
                    ->collapsed(),
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
