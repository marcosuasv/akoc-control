<?php

namespace App\Filament\Admin\Resources\Desarrollos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\Grid;

class DesarrolloInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General del Desarrollo')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('nombre')
                            ->label('Nombre Comercial')
                            ->icon('heroicon-s-building-office-2')
                            ->weight('bold')
                            ->columnSpan(2),

                        TextEntry::make('estatus')
                            ->label('Estatus Actual')
                            ->icon('heroicon-s-tag')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'preventa' => 'warning',
                                'en_construccion' => 'info',
                                'entrega_inmediata' => 'success',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'preventa' => 'Preventa',
                                'en_construccion' => 'En Construcción',
                                'entrega_inmediata' => 'Entrega Inmediata',
                                default => $state,
                            }),
                        
                        TextEntry::make('direccion')
                            ->label('Dirección Completa')
                            ->icon('heroicon-s-map-pin')
                            ->placeholder('Dirección no registrada')
                            ->copyable()
                            ->columnSpanFull(),

                        TextEntry::make('total_unidades')
                            ->label('Unidades Totales')
                            ->icon('heroicon-s-home-modern')
                            ->numeric(decimalPlaces: 0, thousandsSeparator: ',')
                            ->placeholder('0'),
                    ]),
                
                Section::make('Características del Proyecto')
                    ->columns(1)
                    ->schema([
                        TextEntry::make('descripcion')
                            ->label('Descripción Detallada')
                            ->icon('heroicon-s-document-text')
                            ->markdown()
                            ->placeholder('No hay descripción disponible.')
                            ->columnSpanFull(),

                        TextEntry::make('amenidades')
                            ->label('Amenidades Ofrecidas')
                            ->icon('heroicon-s-squares-2x2')
                            ->badge()
                            ->listWithLineBreaks()
                            ->color(function (string $state): string {
                                return match (strtolower($state)) {
                                    'alberca', 'piscina' => 'info',
                                    'gimnasio', 'gym' => 'success',
                                    'seguridad 24/7', 'vigilancia' => 'danger',
                                    'roof garden / asadores', 'asadores', 'rooftop' => 'warning',
                                    'coworking', 'business center' => 'primary',
                                    'salón de usos múltiples', 'sum' => 'secondary',
                                    'ludoteca / kids club', 'kids club' => 'success',
                                    'pet friendly / pet park', 'pet park' => 'teal',
                                    'áreas verdes', 'jardines' => 'success',
                                    'estacionamiento' => 'gray',
                                    default => 'gray',
                                };
                            })
                            ->placeholder('Sin amenidades registradas.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Datos del Sistema')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Fecha de Creación')
                            ->icon('heroicon-s-calendar-days')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),
                        
                        TextEntry::make('updated_at')
                            ->label('Última Modificación')
                            ->icon('heroicon-s-clock')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),
                    ]),
            ]);
    }
}