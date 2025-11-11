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
        $clienteId = request()->query('cliente_id');
        parent::mount();
        if ($clienteId) {
            $this->form->fill([
                'cliente_id' => $clienteId,
            ]);
        }
    }
}