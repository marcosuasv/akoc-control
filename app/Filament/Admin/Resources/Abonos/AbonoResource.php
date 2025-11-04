<?php

namespace App\Filament\Admin\Resources\Abonos;

use App\Filament\Admin\Resources\Abonos\Pages\CreateAbono;
use App\Filament\Admin\Resources\Abonos\Pages\EditAbono;
use App\Filament\Admin\Resources\Abonos\Pages\ListAbonos;
use App\Filament\Admin\Resources\Abonos\Pages\ViewAbono;
use App\Filament\Admin\Resources\Abonos\Schemas\AbonoForm;
use App\Filament\Admin\Resources\Abonos\Schemas\AbonoInfolist;
use App\Filament\Admin\Resources\Abonos\Tables\AbonosTable;
use App\Models\Abono;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AbonoResource extends Resource
{
    protected static ?string $model = Abono::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ReceiptRefund;
    protected static string|UnitEnum|null $navigationGroup = 'Depositos y Finanzas';


    protected static ?string $recordTitleAttribute = 'Abono';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return AbonoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AbonoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AbonosTable::configure($table);
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
            'index' => ListAbonos::route('/'),
            'create' => CreateAbono::route('/create'),
            'view' => ViewAbono::route('/{record}'),
            'edit' => EditAbono::route('/{record}/edit'),
        ];
    }
}
