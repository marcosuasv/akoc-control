<?php

namespace App\Filament\Admin\Resources\Cotizacions\Pages;

use App\Filament\Admin\Resources\Cotizacions\CotizacionResource; // <-- Verifica esta ruta
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCotizacions extends ListRecords
{
    protected static string $resource = CotizacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}