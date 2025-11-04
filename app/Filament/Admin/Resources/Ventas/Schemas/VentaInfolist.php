<?php

namespace App\Filament\Admin\Resources\Ventas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\Model;
class VentaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Condiciones Financieras de la Venta')
                    ->icon('heroicon-s-banknotes')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('fecha')
                            ->label('Fecha de Venta')
                            ->date()
                            ->icon('heroicon-s-calendar-days'),

                        TextEntry::make('monto_total_venta')
                            ->label('Monto Total de la Venta')
                            ->formatStateUsing(fn (float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                            ->icon('heroicon-s-currency-dollar'),

                        TextEntry::make('enganche')
                            ->label('Enganche')
                            ->formatStateUsing(fn (float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                            ->icon('heroicon-s-banknotes'),

                        TextEntry::make('n_pagos')
                            ->label('Número de Pagos')
                            ->numeric()
                            ->icon('heroicon-s-hashtag'),

                        TextEntry::make('frecuencia_pagos')
                            ->label('Frecuencia de Pagos')
                            ->icon('heroicon-s-arrow-path-rounded-square')
                            ->badge(),
                    ]),
                Section::make('Información del Departamento')
                    ->icon('heroicon-s-building-office')
                    ->columns(4)
                    ->relationship('departamento')
                    ->schema([
                        TextEntry::make('numero')
                            ->label('Número / Unidad')
                            ->badge(),
                        TextEntry::make('piso')
                            ->label('Piso'),
                        TextEntry::make('modelo')
                            ->label('Modelo'),
                        TextEntry::make('precio')
                            ->label('Precio de Lista')
                            ->formatStateUsing(fn (float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN'),
                        TextEntry::make('m2_construccion')
                            ->label('M² Construcción')
                            ->suffix(' m²'),
                        TextEntry::make('m2_terraza')
                            ->label('M² Terraza')
                            ->suffix(' m²'),
                        TextEntry::make('recamaras')
                            ->label('Recámaras')
                            ->icon('heroicon-s-key'),
                        TextEntry::make('banos')
                            ->label('Baños')
                            ->icon('heroicon-s-sparkles'),
                        TextEntry::make('estacionamientos')
                            ->label('Estacionamientos')
                            ->icon('heroicon-s-truck'),
                    ]),
                Section::make('Cliente(s) de la Venta')
                    ->icon('heroicon-s-users')
                    ->collapsible() 
                    ->schema([
                        RepeatableEntry::make('clientes')
                            ->label('') 
                            ->grid(1) 
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('nombre_completo')
                                            ->label('Nombre Cliente')
                                            
                                            ->getStateUsing(fn(\App\Models\Cliente $record): string => "{$record->nombre} {$record->apellidos}")
                                            ->columnSpanFull() 
                                            ->icon('heroicon-s-user'),

                                        TextEntry::make('correo')
                                            ->label('Correo')
                                            ->icon('heroicon-s-envelope')
                                            ->copyable() // Permite copiar el correo
                                            ->placeholder('N/A'),

                                        TextEntry::make('telefono')
                                            ->label('Teléfono')
                                            ->icon('heroicon-s-phone')
                                            ->copyable() // Permite copiar el teléfono
                                            ->placeholder('N/A'),

                                        TextEntry::make('direccion')
                                            ->label('Dirección')
                                            ->icon('heroicon-s-map-pin')
                                            ->columnSpanFull()
                                            ->placeholder('N/A'),
                                    ])
                            ])
                    ]),
                Section::make('Información del Desarrollo (Proyecto)')
                    ->icon('heroicon-s-building-office-2')
                    ->columns(3)
                    ->collapsible( ) 
                    ->schema([
                        TextEntry::make('departamento.desarrollo.nombre')
                            ->label('Nombre del Proyecto')
                            ->columnSpanFull(),
                        TextEntry::make('departamento.desarrollo.direccion')
                            ->label('Dirección del Proyecto')
                            ->icon('heroicon-s-map-pin')
                            ->copyable()
                            ->columnSpan(2),
                        TextEntry::make('departamento.desarrollo.estatus')
                            ->label('Estatus del Proyecto')
                            ->badge()
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'preventa' => 'Preventa',
                                'en_construccion' => 'En Construcción',
                                'entrega_inmediata' => 'Entrega Inmediata',
                                default => $state,
                            })
                            ->color(fn(string $state): string => match ($state) {
                                'preventa' => 'info',
                                'en_construccion' => 'warning',
                                'entrega_inmediata' => 'success',
                                default => 'gray',
                            }),
                        TextEntry::make('departamento.desarrollo.total_unidades')
                            ->label('Unidades Totales')
                            ->numeric(),
                        TextEntry::make('departamento.desarrollo.descripcion')
                            ->label('Descripción del Proyecto')
                            ->columnSpanFull()
                            ->placeholder('Sin descripción.'),
                        TextEntry::make('departamento.desarrollo.amenidades')
                            ->label('Amenidades')
                            ->columnSpanFull()
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->placeholder('Sin amenidades.'),
                    ]),
                Section::make('Plan de Pagos')
                    ->icon('heroicon-s-table-cells')
                    ->columnSpanFull()
                    ->collapsible() 
                    ->schema([
                        RepeatableEntry::make('planPagos') 
                            ->label('') 
                            ->columnSpanFull()
                            ->schema([
                               
                                Grid::make(4)
                                    ->schema([
                                        TextEntry::make('numero_pago')
                                            ->label('# Pago')
                                            ->badge(),
                                            
                                        TextEntry::make('fecha_vencimiento')
                                            ->label('Vencimiento')
                                            ->date('d/m/Y'),
                                            
                                        TextEntry::make('monto')
                                            ->label('Monto')
                                            ->formatStateUsing(fn (float $state): string => '$' . number_format($state, 2)),
                                            
                                        TextEntry::make('status')
                                            ->label('Estatus')
                                            ->badge()
                                            ->color(fn(string $state): string => match ($state) {
                                                'pagado' => 'success',
                                                'pendiente' => 'warning',
                                                default => 'gray',
                                            })
                                            ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                                    ])
                            ]),
                    ]),

            ]);
    }
}
