<?php

namespace App\Filament\Admin\Resources\Desarrollos\Pages;

use App\Filament\Admin\Resources\Desarrollos\DesarrolloResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDesarrollo extends ViewRecord
{
    protected static string $resource = DesarrolloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
