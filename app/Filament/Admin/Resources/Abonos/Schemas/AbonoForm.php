<?php

namespace App\Filament\Admin\Resources\Abonos\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Models\Abono;
use App\Models\Pago;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AbonoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Aplicar Abono a Pago')
                    ->columns(2)
                    ->components([

                        Select::make('pago_id')
                            ->label('Pago con Saldo Disponible')
                            ->relationship(
                                name: 'pago',
                                modifyQueryUsing: function (Builder $query) {
                                    $query->with(['cliente', 'abonos']) 
                                          ->where('validacion', true)
                                          ->whereRaw(
                                              'cantidad_general > (SELECT IFNULL(SUM(monto), 0) FROM abonos WHERE abonos.pago_id = pagos.id)'
                                          );
                                }
                            )
                            ->getOptionLabelFromRecordUsing(function (Model $record) {
                                $clienteNombre = $record->cliente ? "{$record->cliente->nombre} {$record->cliente->apellidos}" : 'Cliente no asignado';
                                $fecha = $record->fecha ? $record->fecha->format('d/m/Y') : 'Sin fecha';
                                $saldo = $record->saldoRestante;
                                
                                return "Pago ID: {$record->id} | {$clienteNombre} | {$fecha} | Saldo: \${$saldo}";
                            })
                            ->searchable(['id', 'cliente.nombre', 'cliente.apellidos'])
                            ->preload()
                            ->reactive()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('venta_id')
                            ->label('Venta del Cliente')
                            ->options(function (callable $get) { 
                                $pagoId = $get('pago_id');
                                if (!$pagoId) return [];

                                $pago = Pago::with('cliente')->find($pagoId); 
                                if (!$pago || !$pago->cliente) return [];

                                $ventas = Venta::whereHas('clientes', function (Builder $query) use ($pago) {
                                    $query->where('clientes.id', $pago->cliente->id);
                                })
                                ->with(['departamento.desarrollo']) 
                                ->get();
                                
                                return $ventas->mapWithKeys(function ($venta) {
                                    $desarrolloNombre = $venta->departamento?->desarrollo?->nombre ?? 'N/D';
                                    $departamentoNombre = $venta->departamento?->numero ?? 'Sin Depto.';
                                    
                                    return [$venta->id => "Venta ID: {$venta->id} | {$desarrolloNombre} | {$departamentoNombre}"];
                                });
                            })
                            ->visible(fn (callable $get) => $get('pago_id') !== null)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('monto')
                            ->label('Monto del Abono')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->reactive()
                             ->maxValue(function (callable $get, $record) {
                                $pagoId = $get('pago_id');
                                if (!$pagoId) {
                                    return 99999999; // Límite alto si no hay pago
                                }
                            
                                $pago = Pago::find($pagoId);
                                if (!$pago) {
                                    return 0;
                                }

                                $montoPago = (float) $pago->cantidad_general;
                            
                                $query = Abono::where('pago_id', $pagoId);
                                
                                // Si estamos editando, excluimos el abono actual de la suma
                                if ($record) {
                                    $query->where('id', '!=', $record->id);
                                }
                                
                                $totalPrevio = (float) $query->sum('monto');
                                
                                // El valor máximo es el saldo disponible
                                $saldoDisponible = round($montoPago - $totalPrevio, 2);
                            
                                return $saldoDisponible > 0 ? $saldoDisponible : 0;
                            })
                            ->rules([

                                function ($attribute, $value, $fail, callable $get, $record) {
                                    $pagoId = $get('pago_id');
                                    if (!$pagoId) return;

                                    $pago = Pago::find($pagoId);
                                    
                                    $montoPago = $pago->cantidad_general;
                                    $montoAbonoActual = (float) $value;

                                    $query = Abono::where('pago_id', $pagoId);
                                    
                                    if ($record) {
                                        $query->where('id', '!=', $record->id);
                                    }
                                    
                                    $totalPrevio = $query->sum('monto');
                                    $saldoDisponible = $montoPago - $totalPrevio;

                                    if ($montoAbonoActual > $saldoDisponible) {
                                        // $fail sigue siendo la función de callback, solo que sin type-hint
                                        $fail("El monto (US$ {$montoAbonoActual}) excede el saldo disponible del pago (US$ {$saldoDisponible}).");
                                    }
                                }
                            ])
                            ->columnSpan(1),

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
                    ]),
            ]);
    }
}