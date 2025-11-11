<?php

namespace App\Filament\Admin\Resources\Ventas\Pages;

use App\Filament\Admin\Resources\Ventas\VentaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;

class ViewVenta extends ViewRecord
{
    protected static string $resource = VentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
           Actions\Action::make('visualizar_estado_cuenta')
                ->label('Visualizar Estado de Cuenta')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn (Venta $record): string => route('ventas.estado-de-cuenta', $record))
                ->openUrlInNewTab(),
        ];
    }
}
