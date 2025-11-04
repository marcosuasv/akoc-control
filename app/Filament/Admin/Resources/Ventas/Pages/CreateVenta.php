<?php

namespace App\Filament\Admin\Resources\Ventas\Pages;

use App\Filament\Admin\Resources\Ventas\VentaResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Departamento;

class CreateVenta extends CreateRecord
{
    protected static string $resource = VentaResource::class;
    protected function afterCreate(): void
    {
        // '$this->record' contiene el modelo 'Venta' que se acaba de crear
        $venta = $this->record;

        // Buscamos el departamento que se asoció a esta venta
        $departamento = Departamento::find($venta->departamento_id);

        if ($departamento) {
            // Actualizamos su estatus y guardamos
            $departamento->estatus = 'vendido';
            $departamento->save();
        }
    }
}
