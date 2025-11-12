<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use UnitEnum;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class Cobranza extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;
    protected static string|UnitEnum|null $navigationGroup = 'Depositos y Finanzas';
    protected string $view = 'filament.pages.cobranza';
    protected static ?int $navigationSort = 3;
}
