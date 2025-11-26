<?php

namespace App\Filament\Admin\Resources\Pagos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PlanPago;
use Filament\Schemas\Components\Utilities\Set;

class PagoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles del Depósito')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        Select::make('cliente_id')
                            ->relationship('cliente')
                            ->label('Cliente')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->razon_social ?? "{$record->nombre} {$record->apellidos}")
                            ->searchable(['razon_social', 'nombre', 'apellidos'])
                            ->preload()
                            ->required()
                            ->reactive()
                            ->disabledOn('edit')
                            ->columnSpanFull(),

                        TextInput::make('cantidad_general')
                            ->label('Cantidad Total')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->reactive()
                            ->debounce('500ms')
                            ->columnSpan(1),

                        DatePicker::make('fecha')
                            ->label('Fecha del Depósito')
                            ->required()
                            ->columnSpan(1),

                        Select::make('metodo_pago')
                            ->label('Método de Depósito')
                            ->options([
                                'transferencia' => 'Transferencia',
                                'efectivo' => 'Efectivo',
                                'tarjeta' => 'Tarjeta',
                                'cheque' => 'Cheque',
                                'otro' => 'Otro',
                            ])
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('referencia')
                            ->label('Referencia')
                            ->helperText('N° de cheque, ID de transferencia, etc.')
                            ->default(null)
                            ->columnSpan(1),

                        Textarea::make('comentarios')
                            ->default(null)
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Estado y Adjuntos')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        Toggle::make('validacion')
                            ->label('Depósito Validado')
                            ->helperText('Indica si el depósito ya fue confirmado/verificado. La validación permite aplicar abonos a ventas.')
                            ->required()
                            ->default(false)
                            ->reactive()
                            ->columnSpan(1),

                        FileUpload::make('adjuntar_archivo')
                            ->label('Comprobante de Depósito')
                            ->directory('comprobantes_pago')
                            ->visibility('private')
                            ->default(null)
                            ->columnSpan(1),
                    ]),

                Section::make('Aplicar Monto a Venta(s)')
                    ->description('Distribuye el monto de este depósito en las cuotas pendientes del cliente.')
                    ->icon('heroicon-s-clipboard-document-list')
                    ->collapsible()
                    ->columnSpanFull()
                    ->visible(fn (callable $get, string $operation): bool =>
                        $get('validacion') === true
                    )
                    ->schema([
                        Repeater::make('abonos')
                            ->label('Abonos')
                            ->relationship()
                            ->addActionLabel('Agregar Abono a Cuota')
                            ->columns(2)
                            ->defaultItems(0)
                            ->cloneable()
                            ->required(fn (callable $get): bool => (bool) $get('validacion'))
                            ->schema([
                                Select::make('venta_id_filter')
                                    ->label('Venta del Cliente')
                                    ->disabledOn('edit')
                                    ->afterStateHydrated(function (Select $component, $record) {
                                        if ($record && $record->plan_pago_id) {
                                            $plan = PlanPago::find($record->plan_pago_id);
                                            if ($plan) {
                                                $component->state($plan->venta_id);
                                            }
                                        }
                                    })
                                    ->options(function (callable $get) {
                                        $clienteId = $get('../../cliente_id');
                                        if (!$clienteId) return [];
                                        $ventas = Venta::whereHas('clientes', function (Builder $query) use ($clienteId) {
                                            $query->where('clientes.id', $clienteId);
                                        })
                                        ->with(['departamento.desarrollo'])
                                        ->get();
                                        return $ventas->mapWithKeys(function ($venta) {
                                            $desarrolloNombre = $venta->departamento?->desarrollo?->nombre ?? 'N/D';
                                            $departamentoNombre = $venta->departamento?->numero ?? 'Sin Depto.';
                                            return [$venta->id => "Venta ID: {$venta->id} | {$desarrolloNombre} | {$departamentoNombre}"];
                                        });
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->dehydrated(false)
                                    ->columnSpanFull(),

                                Select::make('plan_pago_id')
                                    ->label('Cuota Pendiente (Plan de Pago)')
                                    ->disabledOn('edit')
                                    ->options(function (callable $get) {
                                        $ventaId = $get('venta_id_filter');
                                        if (!$ventaId) return [0 => 'Seleccione una venta primero'];

                                        $planes = PlanPago::where('venta_id', $ventaId)->get();

                                        return $planes->mapWithKeys(fn (PlanPago $plan) => [
                                            $plan->id => "Cuota #{$plan->numero_pago} (Vence: {$plan->fecha_vencimiento}) - Saldo: \${$plan->saldo}"
                                        ]);
                                    })
                                    ->live()
                                    ->searchable()
                                    ->required()
                                    ->distinct()
                                    ->columnSpan(1)
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        $plan = PlanPago::find($state);
                                        if ($plan) {
                                            $set('monto', $plan->saldo);
                                        }
                                    }),

                                TextInput::make('monto')
                                    ->label('Monto del Abono')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')
                                    ->columnSpan(1)
                                    ->maxValue(function (callable $get) {
                                        $plan = PlanPago::find($get('plan_pago_id'));
                                        return $plan ? $plan->saldo : 9999999;
                                    }),

                                DatePicker::make('fecha_abono')
                                    ->label('Fecha del Abono')
                                    ->required()
                                    ->native(false)
                                    ->default(now())
                                    ->columnSpan(1),

                                Textarea::make('comentarios')
                                    ->rows(4)
                                    ->nullable()
                                    ->columnSpanFull(),

                                Hidden::make('user_id')
                                    ->default(auth()->id())
                                    ->required(),
                            ])
                            ->rules([
                                fn (callable $get): \Closure => function ($attribute, $value, $fail) use ($get) {
                                    if (!(bool) $get('validacion')) return;

                                    $montoTotalPago = (float) $get('cantidad_general');
                                    $totalAbonosUI = collect($value)->sum('monto');

                                    if (round($totalAbonosUI, 2) > round($montoTotalPago, 2)) {
                                        $fail("La suma total de los abonos (\$${totalAbonosUI}) no puede exceder el monto total del depósito (\$${montoTotalPago}).");
                                    }
                                }
                            ])
                            ->validationMessages([
                                'required' => 'Debe agregar al menos un abono si el depósito está validado.',
                                'rules.0' => 'La suma total de los abonos excede el monto del depósito.',
                            ]),
                    ])
            ]);
    }
}