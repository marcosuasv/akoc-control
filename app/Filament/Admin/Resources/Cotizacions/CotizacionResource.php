<?php

namespace App\Filament\Admin\Resources\Cotizacions;

use App\Filament\Admin\Resources\Cotizacions\Pages; 
use App\Filament\Admin\Resources\Cotizacions\Schemas\CotizacionForm;
use App\Filament\Admin\Resources\Cotizacions\Tables\CotizacionsTable;
use App\Models\Cotizacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\ViewField;

class CotizacionResource extends Resource
{
    protected static ?string $model = Cotizacion::class;

    protected static ?string $slug = 'cotizaciones';
    protected static ?string $modelLabel = 'cotización';
    protected static ?string $pluralModelLabel = 'cotizaciones';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-currency-dollar';

    public static function canViewAny(): bool
    {
        return true; 
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }





    // Inyectamos el esquema usando la clase desacoplada como en Ventas
    public static function form(Schema $schema): Schema
    {
        return CotizacionForm::configure($schema);
    }

    // Inyectamos la tabla usando la clase desacoplada como en Ventas
    public static function table(Table $table): Table
    {
        return CotizacionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCotizacions::route('/'),
            'create' => Pages\CreateCotizacion::route('/create'),
            'edit' => Pages\EditCotizacion::route('/{record}/edit'),
        ];
    }
}