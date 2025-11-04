<?php

namespace App\Filament\Admin\Resources\Pagos\Pages;

use App\Filament\Admin\Resources\Pagos\PagoResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreatePago extends CreateRecord
{
    protected static string $resource = PagoResource::class;
}