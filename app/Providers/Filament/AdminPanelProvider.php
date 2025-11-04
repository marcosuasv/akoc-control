<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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
use App\Http\Middleware\CheckIsAdmin;
use Resma\FilamentAwinTheme\FilamentAwinTheme;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use App\Models\Desarrollo;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Admin\Resources\Desarrollos\DesarrolloResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => '#090a36ff',
            ])
            ->topNavigation()
            ->font('Poppins')
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->brandLogo(fn() => asset('images/akon-control.png'))
             ->brandLogoHeight('4rem')
            ->favicon(fn() => asset('images/icono.png'))
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
            /*
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {

                // 1. Preparamos el array de sub-items dinámicos
                $desarrolloSubItems = Desarrollo::all()->map(fn (Desarrollo $desarrollo) =>
                    NavigationItem::make($desarrollo->nombre) // Nombre del desarrollo
                        ->url(DesarrolloResource::getUrl('view', ['record' => $desarrollo]))
                        ->icon('heroicon-o-building-office') // Ícono opcional para sub-items
                )->all();

                return $builder->groups([
                    NavigationGroup::make('Gestión Inmobiliaria')
                        ->items([
                            // ... tus otros enlaces como 'Departamentos'
                        ]),

                    // 2. Creamos el grupo "Desarrollos"
                    NavigationGroup::make('Desarrollos')
                        ->items(array_merge(
                            [
                                // 3. El PRIMER item es el enlace principal al listado
                                NavigationItem::make('Listado de Desarrollos')
                                    ->url(DesarrolloResource::getUrl('index')) // URL al listado general
                                    ->icon('heroicon-o-building-library') // Ícono principal
                                    ->isActiveWhen(fn () => request()->routeIs(DesarrolloResource::getRouteBaseName() . '.index')),
                            ],
                            // 4. Fusionamos con el resto de sub-items dinámicos
                            $desarrolloSubItems
                        )),

                    NavigationGroup::make('Filament Shield')
                        ->items([
                            // ... Users, Roles, etc.
                        ]),
                ]);
            })
*/
            ->plugins([
                FilamentShieldPlugin::make(),
                FilamentAwinTheme::make()
                    ->primaryColor('#030022ff')
            ])
            ->authMiddleware([
                Authenticate::class,
                CheckIsAdmin::class,
            ]);
    }

}
