<?php

namespace App\Filament\Admin\Resources\Abonos\Pages;

use App\Filament\Admin\Resources\Abonos\AbonoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAbono extends EditRecord
{
    protected static string $resource = AbonoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
