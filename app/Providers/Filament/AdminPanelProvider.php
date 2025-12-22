<?php

// namespace App\Providers\Filament;

// use App\Http\Middleware\RoleAdmin;
// use Filament\Http\Middleware\Authenticate;
// use Filament\Http\Middleware\DisableBladeIconComponents;
// use Filament\Http\Middleware\DispatchServingFilamentEvent;
// use Filament\Pages;
// use Filament\Panel;
// use Filament\PanelProvider;
// use Filament\Support\Colors\Color;
// use Filament\Widgets;
// use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
// use Illuminate\Cookie\Middleware\EncryptCookies;
// use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
// use Illuminate\Routing\Middleware\SubstituteBindings;
// use Illuminate\Session\Middleware\AuthenticateSession;
// use Illuminate\Session\Middleware\StartSession;
// use Illuminate\View\Middleware\ShareErrorsFromSession;

// class AdminPanelProvider extends PanelProvider
// {
//     public function panel(Panel $panel): Panel
//     {
//         return $panel
//             ->sidebarCollapsibleOnDesktop(true)
//             ->id('admin')
//             ->path('admin')
//             ->colors([
//                 'primary' => Color::Amber,
//             ])
//             ->login()
//             ->viteTheme('resources/css/filament/operator/theme.css')
//             ->navigationGroups([
//                 'Layanan Tipe A',
//                 'Layanan Tipe B',
//                 'Layanan Komplain',
//                 'Master',
//                 'Report/Report Tipe A',
//                 'Report/Report Tipe B',
//                 'Report/Report Grafik',
//                 'Report/Report Pemadanan',
//                 'Report/Report Dinsos',
//                 'Report/Laporan Kuisioner',
//             ])
//             ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Resources')
//             ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Pages')
//             ->pages([
//                 Pages\Dashboard::class,
//             ])
//             ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Widgets')
//             ->widgets([
//                 Widgets\AccountWidget::class,
//                 Widgets\FilamentInfoWidget::class,
//             ])
//             ->middleware([
//                 EncryptCookies::class,
//                 AddQueuedCookiesToResponse::class,
//                 StartSession::class,
//                 AuthenticateSession::class,
//                 ShareErrorsFromSession::class,
//                 VerifyCsrfToken::class,
//                 SubstituteBindings::class,
//                 DisableBladeIconComponents::class,
//                 DispatchServingFilamentEvent::class,
//                 RoleAdmin::class,
//             ])
//             ->authMiddleware([
//                 Authenticate::class,
//             ]);
//     }
// }

namespace App\Providers\Filament;

use App\Http\Middleware\RoleAdmin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\ReportPemadananDataResource;
use App\Filament\Resources\ReportPemadananGrafikResource;

use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationBuilder;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarCollapsibleOnDesktop(true)
            ->id('admin')
            ->path('admin')
            ->login()
            ->viteTheme('resources/css/filament/operator/theme.css')
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            // ->navigationItems([
            //     NavigationItem::make('Analytics')
            //         ->hidden(fn (): bool => auth()->user()->can('list'))
            // ])
            ->navigationGroups([
                // 'Pelayanan Tipe A',
                // 'Pelayanan Tipe B',
                // 'Layanan Komplain',
                // 'Master',
                // 'Report/Report Tipe A',
                // 'Report/Report Tipe B',
                // 'Report/Report Grafik',
                // 'Report/Report Pemadanan',
                // 'Report/Report Dinsos',
                // 'Report/Laporan Kuisioner',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
                // RoleAdmin::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}