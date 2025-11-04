<?php

namespace App\Filament\Admin\Resources\Pagos;

use App\Filament\Admin\Resources\Pagos\Pages\CreatePago;
use App\Filament\Admin\Resources\Pagos\Pages\EditPago;
use App\Filament\Admin\Resources\Pagos\Pages\ListPagos;
use App\Filament\Admin\Resources\Pagos\Pages\ViewPago;
use App\Filament\Admin\Resources\Pagos\Schemas\PagoForm;
use App\Filament\Admin\Resources\Pagos\Schemas\PagoInfolist;
use App\Filament\Admin\Resources\Pagos\Tables\PagosTable;
use App\Models\Pago;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class PagoResource extends Resource
{
    protected static ?string $model = Pago::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;
    protected static string|UnitEnum|null $navigationGroup = 'Depositos y Finanzas';
    protected static ?string $modelLabel = 'Depósito';
    protected static ?string $pluralModelLabel = 'Depósitos';
     protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'Guía de Deposito';

    public static function form(Schema $schema): Schema
    {
        return PagoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PagoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPagos::route('/'),
            'create' => CreatePago::route('/create'),
            'view' => ViewPago::route('/{record}'),
            'edit' => EditPago::route('/{record}/edit'),
        ];
    }
}
