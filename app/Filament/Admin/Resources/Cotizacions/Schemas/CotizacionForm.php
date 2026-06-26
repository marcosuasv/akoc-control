<?php

namespace App\Filament\Admin\Resources\Cotizacions\Schemas;

use App\Models\Departamento;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;

class CotizacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Section::make('Configuración General de la Propuesta')
                    ->description('Selección del prospecto, unidad comercial y vigencia del plan.')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('cliente_id')
                                ->relationship('cliente', 'nombre')
                                ->getOptionLabelFromRecordUsing(fn ($record) => $record->nombre_completo)
                                ->searchable()
                                ->preload()
                                ->required()
                                ->prefixIcon('heroicon-o-user'),

                            Select::make('departamento_id')
                                ->relationship('departamento', 'numero')
                                ->getOptionLabelFromRecordUsing(fn ($record) => "Depto: {$record->numero} - Piso {$record->piso} ({$record->desarrollo?->nombre})")
                                ->searchable()
                                ->preload()
                                ->required()
                                ->prefixIcon('heroicon-o-home-modern')
                                ->live()
                                ->afterStateUpdated(function ($get, $set, ?string $state) {
                                    if (! $state) return;
                                    
                                    $depto = Departamento::find($state);
                                    if ($depto) {
                                        $set('precio_departamento', $depto->precio);
                                        static::calcularCorrida($get, $set);
                                    }
                                }),

                            DatePicker::make('fecha_vencimiento')
                                ->label('Vencimiento de la Oferta')
                                ->default(now()->addDays(15))
                                ->required()
                                ->prefixIcon('heroicon-o-calendar'),
                        ]),

                        // LO METEMOS AQUÍ EN MEDIO: Así queda dentro de la tarjeta general de la sección
                        View::make('filament.components.cotizador-render')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                // SECCIÓN FINANCIERA TRADICIONAL
                Section::make('Corrida Financiera Comercial y Plan de Pagos')
                    ->description('Modifica el enganche y plazos para actualizar la proyección en tiempo real.')
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('precio_departamento')
                                ->label('Precio de Venta')
                                ->numeric()
                                ->prefix('$')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn ($get, $set) => static::calcularCorrida($get, $set)),

                            TextInput::make('porcentaje_enganche')
                                ->label('% Anticipo (Enganche)')
                                ->numeric()
                                ->suffix('%')
                                ->default(20)
                                ->live()
                                ->afterStateUpdated(fn ($get, $set) => static::calcularCorrida($get, $set)),

                            TextInput::make('monto_enganche')
                                ->label('Suma de Anticipo')
                                ->numeric()
                                ->prefix('$')
                                ->disabled()
                                ->dehydrated()
                                ->extraAttributes(['class' => 'bg-gray-50 dark:bg-gray-800 font-semibold border-dashed']),

                            Select::make('estatus')
                                ->options([
                                    'borrador' => 'Borrador',
                                    'enviada' => 'Enviada al Cliente',
                                    'aceptada' => 'Aceptada (Lista para Venta)',
                                    'vencida' => 'Vencida',
                                ])
                                ->default('borrador')
                                ->required()
                                ->native(false),
                        ]),

                        Grid::make(3)->schema([
                            TextInput::make('numero_pagos')
                                ->label('Plazo (Mensualidades)')
                                ->numeric()
                                ->default(12)
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn ($get, $set) => static::calcularCorrida($get, $set)),

                            Select::make('frecuencia_pagos')
                                ->label('Frecuencia del Esquema')
                                ->options([
                                    'mensual' => 'Mensual',
                                    'trimestral' => 'Trimestral',
                                ])
                                ->default('mensual')
                                ->required()
                                ->native(false),

                            TextInput::make('monto_pago_periodico')
                                ->label('Importe Mensualidad')
                                ->numeric()
                                ->prefix('$')
                                ->disabled()
                                ->dehydrated()
                                ->extraAttributes([
                                    'style' => 'font-size: 1.15rem; font-weight: 800; color: #10b981;',
                                    'class' => 'bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-500'
                                ]),
                        ]),

                        Textarea::make('notas')
                            ->label('Cláusulas o Condiciones Especiales de la Propuesta')
                            ->rows(2)
                            ->placeholder('Ej: Apartado desde $100,000.00 M.N. Todas las áreas son estimadas...'),
                    ])
                    ->columnSpanFull(),

                Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }

    public static function calcularCorrida($get, $set): void
    {
        $precio = (float) $get('precio_departamento') ?? 0;
        $porcentajeEnganche = (float) $get('porcentaje_enganche') ?? 0;
        $pagos = (int) $get('numero_pagos') ?? 1;

        $montoEnganche = $precio * ($porcentajeEnganche / 100);
        $set('monto_enganche', round($montoEnganche, 2));

        $saldoFinanciar = $precio - $montoEnganche;
        $montoMensual = $pagos > 0 ? ($saldoFinanciar / $pagos) : 0;

        $set('monto_pago_periodico', round($montoMensual, 2));
    }
}