<?php

namespace App\Filament\Admin\Resources\Ventas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Departamento;
use App\Models\Venta;
use App\Models\Desarrollo;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\DB;

class VentasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                'departamento.desarrollo.nombre'
            ])
            ->defaultGroup('departamento.desarrollo.nombre')
            ->columns([
                TextColumn::make('departamento.numero')
                    ->label('Depto.')
                    ->sortable()
                    ->searchable()
                    ->description(fn(Venta $record): string => "Modelo: {$record->departamento->modelo}"),
                
                TextColumn::make('clientes.razon_social')
                    ->label('Cliente(s)')
                    ->bulleted()
                    ->getStateUsing(fn($record) => $record->clientes->map(fn($cliente) => $cliente->razon_social ?? "{$cliente->nombre} {$cliente->apellidos}")->all())
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('clientes', function (Builder $q) use ($search) {
                            $q->where('razon_social', 'like', "%{$search}%")
                              ->orWhere(DB::raw("CONCAT(nombre, ' ', apellidos)"), 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('fecha')
                    ->label('Fecha Venta')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('monto_total_venta')
                    ->label('Monto Total')
                      ->formatStateUsing(fn (float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                     ->color('success')
                      ->sortable(),

                TextColumn::make('enganche')
                     ->formatStateUsing(fn (float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                    ->sortable()
                    ->color('warning')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('n_pagos')
                    ->label('N° Pagos')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('enganche')
                    ->label('Tiene Enganche')
                    ->boolean()
                    ->trueLabel('Con Enganche')
                    ->falseLabel('Sin Enganche')
                    ->queries(
                        true: fn(Builder $query) => $query->where('enganche', '>', 0),
                        false: fn(Builder $query) => $query->where('enganche', '=', 0),
                    ),

                Filter::make('fecha')
                    ->form([
                        DatePicker::make('fecha_desde')->label('Ventas desde'),
                        DatePicker::make('fecha_hasta')->label('Ventas hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_desde'],
                                fn(Builder $query, $date): Builder => $query->whereDate('fecha', '>=', $date),
                            )
                            ->when(
                                $data['fecha_hasta'],
                                fn(Builder $query, $date): Builder => $query->whereDate('fecha', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()->icon('heroicon-s-eye'),
                    EditAction::make()->icon('heroicon-s-pencil'),
                    DeleteAction::make()->icon('heroicon-s-trash'),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('fecha', 'desc');
    }
}