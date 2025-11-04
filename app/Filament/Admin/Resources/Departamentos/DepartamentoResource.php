<?php

namespace App\Filament\Admin\Resources\Departamentos;

use App\Filament\Admin\Resources\Departamentos\Pages\CreateDepartamento;
use App\Filament\Admin\Resources\Departamentos\Pages\EditDepartamento;
use App\Filament\Admin\Resources\Departamentos\Pages\ListDepartamentos;
use App\Filament\Admin\Resources\Departamentos\Pages\ViewDepartamento;
use App\Filament\Admin\Resources\Departamentos\Schemas\DepartamentoForm;
use App\Filament\Admin\Resources\Departamentos\Schemas\DepartamentoInfolist;
use App\Filament\Admin\Resources\Departamentos\Tables\DepartamentosTable;
use App\Models\Departamento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DepartamentoResource extends Resource
{
    protected static ?string $model = Departamento::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice2;
    protected static string|UnitEnum|null $navigationGroup = 'Gestión Inmobiliaria';
    protected static ?string $recordTitleAttribute = 'Departamento';
        protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return DepartamentoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DepartamentoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartamentosTable::configure($table);
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
            'index' => ListDepartamentos::route('/'),
            'create' => CreateDepartamento::route('/create'),
            'view' => ViewDepartamento::route('/{record}'),
            'edit' => EditDepartamento::route('/{record}/edit'),
        ];
    }
}
