<?php

namespace App\Filament\Admin\Resources\Pagos\Pages;

use App\Filament\Admin\Resources\Pagos\PagoResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreatePago extends CreateRecord
{
    protected static string $resource = PagoResource::class;
    public function mount(): void
    {
        parent::mount();
        $this->form->fill([
            'cliente_id' => request()->query('cliente_id'),
            'cantidad_general' => request()->query('cantidad_general'),
            // Si tienes otros campos que quieras llenar, agrégalos aquí
        ]);
    }
}