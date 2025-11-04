<?php

namespace App\Filament\Admin\Resources\Abonos\Pages;

use App\Filament\Admin\Resources\Abonos\AbonoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAbono extends ViewRecord
{
    protected static string $resource = AbonoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
