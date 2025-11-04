<?php

namespace App\Filament\Admin\Resources\Desarrollos;

use App\Filament\Admin\Resources\Desarrollos\Pages\CreateDesarrollo;
use App\Filament\Admin\Resources\Desarrollos\Pages\EditDesarrollo;
use App\Filament\Admin\Resources\Desarrollos\Pages\ListDesarrollos;
use App\Filament\Admin\Resources\Desarrollos\Pages\ViewDesarrollo;
use App\Filament\Admin\Resources\Desarrollos\Schemas\DesarrolloForm;
use App\Filament\Admin\Resources\Desarrollos\Schemas\DesarrolloInfolist;
use App\Filament\Admin\Resources\Desarrollos\Tables\DesarrollosTable;
use App\Models\Desarrollo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DesarrolloResource extends Resource
{
    protected static ?string $model = Desarrollo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::MapPin;
 protected static string|UnitEnum|null $navigationGroup = 'Gestión Inmobiliaria';
    protected static ?string $recordTitleAttribute = 'Desarrollo';
    protected static ?int $navigationSort = 1;
    public static function form(Schema $schema): Schema
    {
        return DesarrolloForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DesarrolloInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DesarrollosTable::configure($table);
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
            'index' => ListDesarrollos::route('/'),
            'create' => CreateDesarrollo::route('/create'),
            'view' => ViewDesarrollo::route('/{record}'),
            'edit' => EditDesarrollo::route('/{record}/edit'),
        ];
    }
}
