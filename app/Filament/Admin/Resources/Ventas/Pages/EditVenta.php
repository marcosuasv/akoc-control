<?php

namespace App\Filament\Admin\Resources\Ventas\Pages;

use App\Filament\Admin\Resources\Ventas\VentaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVenta extends EditRecord
{
    protected static string $resource = VentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
   protected function mutateFormDataBeforeFill(array $data): array
{
    $this->record->load('departamento.desarrollo');
    $data['desarrollo_id_filter'] = optional($this->record->departamento)->desarrollo_id;
    return $data;
}
}
