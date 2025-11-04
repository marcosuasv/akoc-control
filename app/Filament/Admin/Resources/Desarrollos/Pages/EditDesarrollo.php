<?php

namespace App\Filament\Admin\Resources\Desarrollos\Pages;

use App\Filament\Admin\Resources\Desarrollos\DesarrolloResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDesarrollo extends EditRecord
{
    protected static string $resource = DesarrolloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
