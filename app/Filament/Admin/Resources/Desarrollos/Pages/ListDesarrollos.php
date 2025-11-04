<?php

namespace App\Filament\Admin\Resources\Desarrollos\Pages;

use App\Filament\Admin\Resources\Desarrollos\DesarrolloResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDesarrollos extends ListRecords
{
    protected static string $resource = DesarrolloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
