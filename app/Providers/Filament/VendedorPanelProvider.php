<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Resources\Abonos\AbonoResource;
use App\Filament\Admin\Resources\Departamentos\DepartamentoResource;
use App\Filament\Admin\Resources\Desarrollos\DesarrolloResource;
use App\Filament\Admin\Resources\Pagos\PagoResource;
use App\Models\Departamento;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Resma\FilamentAwinTheme\FilamentAwinTheme;
use App\Filament\Admin\Resources\Clientes\ClienteResource;
use App\Filament\Admin\Resources\Ventas\VentaResource;

class VendedorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('vendedor')
            ->path('vendedor')
            ->topNavigation()
            ->font('Poppins')
            ->discoverResources(in: app_path('Filament/Vendedor/Resources'), for: 'App\Filament\Vendedor\Resources')
            ->discoverPages(in: app_path('Filament/Vendedor/Pages'), for: 'App\Filament\Vendedor\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->brandLogo(fn() => asset('images/akon-control.png'))
             ->brandLogoHeight('4rem')
            ->favicon(fn() => asset('images/icono.png'))
            ->discoverWidgets(in: app_path('Filament/Vendedor/Widgets'), for: 'App\Filament\Vendedor\Widgets')
            ->widgets([
                AccountWidget::class,
                //FilamentInfoWidget::class,
                //\App\Filament\Widgets\ListadoCobranzaWidget::class,
            ])
            ->resources([
                ClienteResource::class,
                VentaResource::class,
                PagoResource::class,
                AbonoResource::class,
                DepartamentoResource::class,
                DesarrolloResource::class,
            ])
               ->plugins([
                FilamentAwinTheme::make()
                    ->primaryColor('#c0a062')
                    
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
