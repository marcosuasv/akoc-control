<?php

namespace App\Filament\Admin\Resources\Abonos\Pages;

use App\Filament\Admin\Resources\Abonos\AbonoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAbonos extends ListRecords
{
    protected static string $resource = AbonoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
