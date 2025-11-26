<?php

namespace App\Filament\Admin\Resources\Ventas\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use App\Models\Desarrollo;
use App\Models\Departamento;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Cliente;
use Filament\Forms\Components\Repeater\TableColumn;

class VentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Información de la Venta')
                ->description('Selecciona el desarrollo, departamento y clientes asociados.')
                ->schema([
                    Select::make('desarrollo_id_filter')
                        ->label('Desarrollo')
                        ->options(Desarrollo::pluck('nombre', 'id'))
                        ->searchable()->preload()->live()
                        ->afterStateUpdated(fn(Set $set) => $set('departamento_id', null))
                        ->dehydrated(false)
                        ->default(fn(?Model $record) => $record?->departamento?->desarrollo_id)
                        ->disabled(fn(string $operation) => $operation !== 'create')
                        ->columnSpanFull(),

                    Select::make('departamento_id')
                        ->label('Departamento')
                        ->relationship(
                            name: 'departamento',
                            titleAttribute: 'numero',
                            modifyQueryUsing: function ($query, Get $get, $livewire) {
                                $desarrolloId = $get('desarrollo_id_filter');

                                if (!$desarrolloId && $livewire->record) {
                                    $desarrolloId = $livewire->record?->departamento?->desarrollo_id;
                                }

                                if ($desarrolloId) {
                                    $query->where('desarrollo_id', $desarrolloId);
                                }

                                $query->where(function ($q) use ($livewire) {
                                    $q->where('estatus', 'disponible');
                                    if ($livewire->record && $livewire->record->departamento_id) {
                                        $q->orWhere('id', $livewire->record->departamento_id);
                                    }
                                });

                                return $query;
                            }
                        )
                        ->getOptionLabelUsing(function ($value) {
                            $record = \App\Models\Departamento::find($value);
                            // Aseguramos retornar string vacío en lugar de null si no encuentra el registro
                            return $record ? "N° {$record->numero} - {$record->modelo} (Piso {$record->piso})" : "";
                        })
                        ->getOptionLabelFromRecordUsing(fn(Model $record) => "N° {$record->numero} - {$record->modelo} (Piso {$record->piso})")
                        ->searchable(['numero', 'modelo', 'piso'])
                        ->preload()->required()->live()
                        ->afterStateUpdated(function (Set $set, ?string $state) {
                            if ($state) {
                                $departamento = \App\Models\Departamento::find($state);
                                if ($departamento) {
                                    $set('monto_total_venta', $departamento->precio);
                                    if ($departamento->m2_construccion > 0) {
                                        $set('preciom2', round($departamento->precio / $departamento->m2_construccion, 2));
                                    } else {
                                        $set('preciom2', null);
                                    }
                                }
                            } else {
                                $set('monto_total_venta', null);
                                $set('preciom2', null);
                            }
                        })
                        ->disabled(fn(string $operation) => $operation !== 'create')
                        ->columnSpanFull(),

                    Placeholder::make('vista_previa_departamento')
                        ->hidden(fn(Get $get) => !$get('departamento_id'))
                        ->content(function (Get $get) {
                            $departamento = Departamento::find($get('departamento_id'));
                            if (!$departamento)
                                return null;
                            $precioM2 = ($departamento->m2_construccion > 0) ? number_format(round($departamento->precio / $departamento->m2_construccion, 2), 2) : 'N/A';
                            return new HtmlString('<div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"><h4 class="mb-2 font-semibold">Detalles del Departamento</h4><strong>Precio:</strong> $' . number_format($departamento->precio, 2) . '<br><strong>Estatus:</strong> ' . e($departamento->estatus) . '<br><strong>M² Const total.:</strong> ' . e($departamento->m2_construccion) . ' m² | <strong>Precio por M²:</strong> $' . $precioM2 . '<br><strong>Recámaras:</strong> ' . e($departamento->recamaras) . ' | <strong>Baños:</strong> ' . e($departamento->banos) . ' | <strong>Estacionamientos:</strong> ' . e($departamento->estacionamientos) . '</div>');
                        })->columnSpanFull(),

                    Select::make('clientes')
                        ->label('Cliente(s)')
                        ->relationship('clientes')
                        ->multiple()
                        // SOLUCIÓN: Usamos '??' para evitar devolver null si razon_social está vacío
                        ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->razon_social ?? "{$record->nombre} {$record->apellidos}")
                        ->searchable(['razon_social', 'nombre', 'apellidos']) // Agregué nombre/apellidos por si razon_social es nulo
                        ->preload()
                        ->required()
                        ->createOptionForm([
                            TextInput::make('rfc')->label('RFC')->maxLength(13)->required(),
                            TextInput::make('razon_social')->label('Razón Social')->required()->maxLength(255),
                            TextInput::make('nombre')->label('Nombre')->required(),
                            TextInput::make('apellidos')->label('Apellidos')->required(),
                            TextInput::make('direccion')->label('Dirección')->required(),
                            TextInput::make('telefono')->label('Teléfono')->tel()->required(),
                            TextInput::make('correo')->label('Correo')->email()->required(),
                        ])->columnSpanFull(),
                ])->columns(2),

            Section::make('Detalles Financieros')
                ->description('Define los montos para generar el plan de pagos.')
                ->schema([
                    TextInput::make('enganche')->label('Enganche')->numeric()->prefix('$')->required(),
                    TextInput::make('n_pagos')->label('Número de Pagos')->numeric()->required()->helperText('Número de pagos sin contar el enganche.'),
                    Select::make('frecuencia_pagos')->label('Frecuencia de Pagos')->options(['semanal' => 'Semanal', 'quincenal' => 'Quincenal', 'mensual' => 'Mensual', 'anual' => 'Anual'])->required(),
                    DatePicker::make('fecha')->label('Fecha de Inicio de Pagos')->default(now())->required(),
                    TextInput::make('monto_total_venta')
                        ->label('Monto Total de la Venta')
                        ->numeric()
                        ->prefix('$')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?float $state) {
                            $departamentoId = $get('departamento_id');
                            $departamento = $departamentoId ? \App\Models\Departamento::find($departamentoId) : null;

                            if ($departamento && $departamento->m2_construccion > 0 && $state) {
                                $set('preciom2', round($state / $departamento->m2_construccion, 2));
                            }
                        }),

                    TextInput::make('preciom2')
                        ->label('Precio por m²')
                        ->numeric()
                        ->prefix('$')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?float $state) {
                            $departamentoId = $get('departamento_id');
                            $departamento = $departamentoId ? \App\Models\Departamento::find($departamentoId) : null;

                            if ($departamento && $departamento->m2_construccion > 0 && $state) {
                                $set('monto_total_venta', round($state * $departamento->m2_construccion, 2));
                            }
                        })
                        ->default(function (Get $get) {
                            $departamentoId = $get('departamento_id');
                            if (!$departamentoId)
                                return null;

                            $departamento = \App\Models\Departamento::find($departamentoId);
                            if ($departamento && $departamento->m2_construccion > 0) {
                                return round($departamento->precio / $departamento->m2_construccion, 2);
                            }

                            return null;
                        }),

                    Action::make('generate_plan')
                        ->label('Generar / Actualizar Plan de Pagos')
                        ->icon('heroicon-o-table-cells')
                        ->color('gray')
                        ->action(function (Get $get, Set $set) {
                            $requiredFields = [
                                'monto_total_venta' => 'Monto Total',
                                'enganche' => 'Enganche',
                                'n_pagos' => 'Número de Pagos',
                                'frecuencia_pagos' => 'Frecuencia de Pagos',
                                'fecha' => 'Fecha de Inicio de Pagos',
                            ];

                            foreach ($requiredFields as $field => $label) {
                                if (blank($get($field))) {
                                    Notification::make()
                                        ->title('Faltan Datos para Calcular')
                                        ->body("Por favor, ingresa un valor para el campo '{$label}'.")
                                        ->warning()
                                        ->send();
                                    return;
                                }
                            }

                            $total = (float) $get('monto_total_venta');
                            $enganche = (float) $get('enganche');
                            $nPagos = (int) $get('n_pagos');
                            $frecuencia = $get('frecuencia_pagos');
                            $fechaInicio = Carbon::parse($get('fecha'));

                            $montoAFinanciar = $total - $enganche;
                            if ($montoAFinanciar < 0) {
                                Notification::make()
                                    ->title('Error en el Cálculo')
                                    ->body('El enganche no puede ser mayor al monto total de la venta.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            if ($montoAFinanciar == 0) {
                                $set('planPagos', []);
                                Notification::make()
                                    ->title('Plan de Pagos Vacío')
                                    ->body('El monto a financiar es cero. No se generaron pagos.')
                                    ->info()
                                    ->send();
                                return;
                            }

                            if ($nPagos <= 0) {
                                Notification::make()
                                    ->title('Error en el Cálculo')
                                    ->body('El número de pagos debe ser mayor a cero.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $montoPago = self::calcularCuota($montoAFinanciar, $nPagos);

                            $plan = [];
                            for ($i = 1; $i <= $nPagos; $i++) {
                                $fechaVencimiento = clone $fechaInicio;
                                switch ($frecuencia) {
                                    case 'semanal':
                                        $fechaVencimiento->addWeeks($i);
                                        break;
                                    case 'quincenal':
                                        $fechaVencimiento->addDays($i * 15);
                                        break;
                                    case 'anual':
                                        $fechaVencimiento->addYears($i);
                                        break;
                                    default:
                                        $fechaVencimiento->addMonths($i);
                                        break;
                                }
                                $plan[] = [
                                    'numero_pago' => $i,
                                    'monto' => round($montoPago, 2),
                                    'fecha_vencimiento' => $fechaVencimiento->format('Y-m-d'),
                                    'status' => 'pendiente',
                                ];
                            }
                            $set('planPagos', $plan);
                        }),

                ])
                ->columns(2),

            Section::make('Plan de Pagos Detallado')
                ->description('Aquí puedes editar individualmente cada pago antes de guardar.')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('planPagos')
                        ->label('Plan de Pagos')
                        ->relationship()
                        ->table([
                            TableColumn::make('# Pago')
                                ->width('100px'),
                            TableColumn::make('Fecha Vencimiento')
                                ->width('80px'),
                            TableColumn::make('Monto')
                                ->width('100px'),
                            TableColumn::make('Estatus')
                                ->width('100px'),
                        ])
                        ->schema([
                            TextInput::make('numero_pago')
                                ->readOnly()
                                ->label('# Pago')
                                ->default(fn(Get $get) => count($get('../../planPagos')) + 1)
                                ->hiddenLabel()
                                ->required(),

                            DatePicker::make('fecha_vencimiento')
                                ->label('Vencimiento')
                                ->hiddenLabel()
                                ->required(),

                            TextInput::make('monto')
                                ->numeric()
                                ->prefix('$')
                                ->label('Monto')
                                ->hiddenLabel()
                                ->required(),

                            Select::make('status')
                                ->options(['pendiente' => 'Pendiente', 'pagado' => 'Pagado'])
                                ->default('pendiente')
                                ->label('Status')
                                ->hiddenLabel()
                                ->required(),
                        ])
                        ->grid(2)
                        ->defaultItems(0)
                        ->reorderable(false)
                        ->deletable()
                        ->addable()
                        ->columnSpanFull()

                ]),
        ]);
    }

    private static function calcularCuota(float $principal, int $periodos): float
    {
        if ($periodos <= 0) {
            return 0;
        }

        return $principal / $periodos;
    }
}