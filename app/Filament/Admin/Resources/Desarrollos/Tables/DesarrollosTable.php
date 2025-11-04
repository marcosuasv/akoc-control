<?php

namespace App\Filament\Admin\Resources\Desarrollos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Desarrollo;

class DesarrollosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre del Desarrollo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Desarrollo $record): string => $record->direccion ?? 'Sin dirección'),

                TextColumn::make('total_unidades')
                    ->label('Unidades Totales')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: ',')
                    ->sortable(),

               TextColumn::make('amenidades')
    ->label('Amenidades')
    ->badge()
    ->listWithLineBreaks()
    ->color(function (string $state): string {
        return match (strtolower($state)) {
            'alberca', 'piscina' => 'info', // Azul para Agua
            'gimnasio', 'gym' => 'success', // Verde para Salud/Ejercicio
            'seguridad 24/7', 'vigilancia' => 'danger', // Rojo para Seguridad
            'roof garden / asadores', 'asadores', 'rooftop' => 'warning', // Amarillo/Naranja para Espacios de Comida/Ocio
            'coworking', 'business center' => 'primary', // Azul Oscuro/Principal para Trabajo
            'salón de usos múltiples', 'sum' => 'secondary', // Un color neutral para Eventos
            'ludoteca / kids club', 'kids club' => 'success', // Verde para Niños
            'pet friendly / pet park', 'pet park' => 'teal', // Verde-Azul para Mascotas
            'áreas verdes', 'jardines' => 'success', // Verde para Naturaleza
            'estacionamiento' => 'gray', // Gris para lo Básico
            default => 'gray',
        };
    })
    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('estatus')
                    ->badge()
                    ->label('Estatus')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'preventa' => 'Preventa',
                        'en_construccion' => 'En Construcción',
                        'entrega_inmediata' => 'Entrega Inmediata',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'preventa' => 'warning',
                        'en_construccion' => 'info',
                        'entrega_inmediata' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),
                    
                TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->date('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}