<?php

namespace App\Filament\Vendedor\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int|array
    {
        return 3; // ← aquí defines las 3 columnas
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Vendedor\Widgets\ChartVenta2::class,
            \App\Filament\Vendedor\Widgets\ChartVentasMes2::class,
            \App\Filament\Vendedor\Widgets\ChartPagos2::class,
             
        ];
    }
}