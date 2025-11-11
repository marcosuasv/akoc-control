<?php

namespace App\Filament\Admin\Resources\Departamentos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;
use Illuminate\Support\Facades\Auth;


class DepartamentoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Grid::make(1)
                    ->columnSpan(2)
                    ->schema([
                        Section::make('Ubicación y Características Físicas')
                            ->columns(3)
                            ->schema([
                                TextEntry::make('desarrollo.nombre')
                                    ->label('Desarrollo')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->url(function (Model $record): string {

                                        $user = Auth::user();
                                        if ($user->hasRole('super_admin')) {
                                            return "http://10.2.0.170:8090/admin/desarrollos/{$record->desarrollo_id}";
                                        }

                                        if ($user->hasRole('vendedor')) {
                                            return "http://10.2.0.170:8090/vendedor/desarrollos/{$record->desarrollo_id}";
                                        }

                                        return '#';

                                    })
                                    ->openUrlInNewTab()
                                    ->columnSpanFull(),

                                TextEntry::make('numero')
                                    ->label('Número de Unidad'),

                                TextEntry::make('piso')
                                    ->label('Piso'),

                                TextEntry::make('modelo')
                                    ->label('Modelo'),

                                TextEntry::make('m2_construccion')
                                    ->label('M² Total Construcción')
                                    ->suffix(' m²'),
                                TextEntry::make('precio')
                                    ->label('Precio')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->formatStateUsing(fn(?float $state) => '$' . number_format($state ?? 0, 2, '.', ',') . ' MXN')
                                    ->color('primary'),

                                TextEntry::make('m2_terraza')
                                    ->label('Precio M²')
                                    ->size('md')
                                     ->weight('bold')
                                    ->color(    'secondary')
                                   ->money(currency: 'MXN', locale: 'es_MX') 
                                    ->suffix(' / m²'),

                                TextEntry::make('recamaras')
                                    ->label('Recámaras'),

                                TextEntry::make('banos')
                                    ->label('Baños'),

                                TextEntry::make('estacionamientos')
                                    ->label('Estacionamientos'),
                            ]),

                        Section::make('Estado Financiero de Venta')
                            ->columns(3)
                            ->hidden(fn(Departamento $record) => is_null($record->venta))
                            ->schema([
                                TextEntry::make('venta.monto_total_venta')
                                    ->label('Costo Total de Venta')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->formatStateUsing(fn(?float $state) => '$' . number_format($state ?? 0, 2, '.', ',') . ' MXN')
                                    ->color('primary')
                                    ->columnSpan(2),

                                TextEntry::make('estatus')
                                    ->label('Estatus Venta')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): string => match ($state) {
                                        'Disponible' => 'Disponible',
                                        'Vendido' => 'Vendido',
                                        'Reservado' => 'Reservado',
                                        default => $state,
                                    })
                                    ->color(fn(string $state): string => match ($state) {
                                        'Disponible' => 'success',
                                        'Vendido' => 'danger',
                                        'Reservado' => 'info',
                                        default => 'gray',
                                    }),

                                TextEntry::make('venta.enganche')
                                    ->label('Monto del Enganche')
                                    ->formatStateUsing(fn(?float $state) => '$' . number_format($state ?? 0, 2, '.', ',') . ' MXN')
                                    ->color('info')
                                    ->weight('bold'),

                                TextEntry::make('abonos_transferencia')
                                    ->label('Abonado (Transferencia)')
                                    ->getStateUsing(function (Departamento $record): float {
                                        if (!$record->relationLoaded('venta') || !$record->venta) {
                                            return 0.0;
                                        }
                                        return $record->venta->abonos()
                                            ->whereHas('pago', fn($query) => $query->where('metodo_pago', 'transferencia'))
                                            ->sum('abonos.monto');
                                    })
                                    ->formatStateUsing(fn(float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                                    ->color('gray')
                                    ->weight('medium')
                                    ->hidden(fn (float $state) => $state <= 0),

                                TextEntry::make('abonos_efectivo')
                                    ->label('Abonado (Efectivo)')
                                    ->getStateUsing(function (Departamento $record): float {
                                        if (!$record->relationLoaded('venta') || !$record->venta) {
                                            return 0.0;
                                        }
                                        return $record->venta->abonos()
                                            ->whereHas('pago', fn($query) => $query->where('metodo_pago', 'efectivo'))
                                            ->sum('abonos.monto');
                                    })
                                    ->formatStateUsing(fn(float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                                    ->color('gray')
                                    ->weight('medium')
                                    ->hidden(fn (float $state) => $state <= 0),

                                TextEntry::make('abonos_tarjeta')
                                    ->label('Abonado (Tarjeta)')
                                    ->getStateUsing(function (Departamento $record): float {
                                        if (!$record->relationLoaded('venta') || !$record->venta) {
                                            return 0.0;
                                        }
                                        return $record->venta->abonos()
                                            ->whereHas('pago', fn($query) => $query->where('metodo_pago', 'tarjeta'))
                                            ->sum('abonos.monto');
                                    })
                                    ->formatStateUsing(fn(float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                                    ->color('gray')
                                    ->weight('medium')
                                    ->hidden(fn (float $state) => $state <= 0),

                                TextEntry::make('abonos_cheque')
                                    ->label('Abonado (Cheque)')
                                    ->getStateUsing(function (Departamento $record): float {
                                        if (!$record->relationLoaded('venta') || !$record->venta) {
                                            return 0.0;
                                        }
                                        return $record->venta->abonos()
                                            ->whereHas('pago', fn($query) => $query->where('metodo_pago', 'cheque'))
                                            ->sum('abonos.monto');
                                    })
                                    ->formatStateUsing(fn(float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                                    ->color('gray')
                                    ->weight('medium')
                                    ->hidden(fn (float $state) => $state <= 0),

                                TextEntry::make('abonos_otro')
                                    ->label('Abonado (Otro)')
                                    ->getStateUsing(function (Departamento $record): float {
                                        if (!$record->relationLoaded('venta') || !$record->venta) {
                                            return 0.0;
                                        }
                                        return $record->venta->abonos()
                                            ->whereHas('pago', fn($query) => $query->where('metodo_pago', 'otro'))
                                            ->sum('abonos.monto');
                                    })
                                    ->formatStateUsing(fn(float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                                    ->color('gray')
                                    ->weight('medium')
                                    ->hidden(fn (float $state) => $state <= 0),

                                TextEntry::make('abonos_acumulados')
                                    ->label('Total Abonado (sin enganche)')
                                    ->getStateUsing(function (Departamento $record): float {
                                        if (!$record->relationLoaded('venta') || !$record->venta) {
                                            return 0.0;
                                        }
                                        $abonos = $record->venta->abonos()->sum('abonos.monto') ?? 0.0;
                                        return $abonos;
                                    })
                                    ->formatStateUsing(fn(float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                                    ->color('success')
                                    ->weight('bold'),

                                TextEntry::make('saldo_pendiente')
                                    ->label('Saldo Pendiente')
                                    ->getStateUsing(function (Departamento $record): float {
                                        $costo = $record->venta->monto_total_venta ?? $record->precio ?? 0.0;
                                        $abonado = 0.0;

                                        if (!$record->relationLoaded('venta') || !$record->venta) {
                                            return $costo;
                                        }

                                        $enganche = $record->venta->enganche ?? 0.0;
                                        $abonos = $record->venta->abonos()->sum('abonos.monto') ?? 0.0;
                                        $total_pagado = $enganche + $abonos;

                                        return $costo - $total_pagado;
                                    })
                                    ->formatStateUsing(fn(float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                                    ->color(fn(float $state): string => $state > 0 ? 'danger' : 'success')
                                    ->weight('bold'),

                                TextEntry::make('venta.fecha')
                                    ->label('Fecha de Venta')
                                    ->date('d M Y')
                                    ->placeholder('N/A'),
                            ]),
                    ]),

                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Cliente(s) de la Venta')
                            ->hidden(fn(Departamento $record) => is_null($record->venta))
                            ->schema([
                                RepeatableEntry::make('venta.clientes')
                                    ->label('Clientes')
                                    ->schema([
                                        TextEntry::make('nombre_completo')
                                            ->label('Nombre')
                                            ->weight('bold')
                                            ->url(function (Model $record): string {

                                                $user = Auth::user();
                                                if ($user->hasRole('super_admin')) {
                                                    return "http://10.2.0.170:8090/admin/clientes/{$record->id}";
                                                }

                                                if ($user->hasRole('vendedor')) {
                                                    return "http://10.2.0.170:8090/vendedor/clientes/{$record->id}";
                                                }

                                                return '#';

                                            })
                                            ->openUrlInNewTab(),

                                        TextEntry::make('telefono')
                                            ->label('Teléfono')
                                            ->copyable()
                                            ->placeholder('N/A'),

                                        TextEntry::make('correo')
                                            ->label('Correo')
                                            ->copyable()
                                            ->placeholder('N/A'),
                                    ])
                                    ->contained(true)
                                    ->grid(1)
                                    ->label(''),
                            ]),

                        Section::make('Tiempos de Registro')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Fecha de Creación')
                                    ->dateTime(),
                                TextEntry::make('updated_at')
                                    ->label('Última Actualización')
                                    ->dateTime(),
                            ]),
                    ]),
                
                Section::make('Plan de Pagos de la Venta')
                    ->icon('heroicon-s-table-cells')
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed()
                    ->hidden(fn(Departamento $record) => is_null($record->venta))
                    ->schema([
                        RepeatableEntry::make('venta.planPagos')
                            ->label('') 
                            ->columnSpanFull()
                            ->schema([
                                Grid::make(5) 
                                    ->schema([
                                        TextEntry::make('numero_pago')
                                            ->label('# Pago')
                                            ->badge(),
                                            
                                        TextEntry::make('fecha_vencimiento')
                                            ->label('Vencimiento')
                                            ->date('d/m/Y'),
                                            
                                        TextEntry::make('monto')
                                            ->label('Monto Cuota')
                                            ->formatStateUsing(fn (float $state): string => '$' . number_format($state, 2)),
                                        
                                        TextEntry::make('saldo') 
                                            ->label('Saldo Pendiente')
                                            ->formatStateUsing(fn (float $state): string => '$' . number_format($state, 2))
                                            ->color('warning')
                                            ->visible(fn ($record) => $record->status !== 'pagado'),

                                        TextEntry::make('status')
                                            ->label('Estatus')
                                            ->badge()
                                            ->color(fn(string $state): string => match ($state) {
                                                'pagado' => 'success',
                                                'parcial' => 'info', 
                                                'pendiente' => 'warning',
                                                default => 'gray',
                                            })
                                            ->formatStateUsing(fn(string $state): string => match ($state) { 
                                                'pagado' => 'Pagado',
                                                'parcial' => 'Parcial',
                                                'pendiente' => 'Pendiente',
                                                default => ucfirst($state),
                                            }),
                                    ]),

                                Section::make('Abonos Recibidos en esta Cuota')
                                    ->icon('heroicon-s-clipboard-document-list')
                                    ->collapsible() 
                                    ->collapsed()   
                                    ->columnSpanFull()
                                    ->visible(fn ($record) => $record->abonos->count() > 0)
                                    ->schema([
                                        RepeatableEntry::make('abonos')
                                            ->label('')
                                            ->columnSpanFull()
                                            ->schema([
                                                Grid::make(4)
                                                    ->schema([
                                                        TextEntry::make('pago.id') 
                                                            ->label('ID Depósito Origen')
                                                            ->badge()
                                                            ->url(function (Model $record): string {
                                                                $user = Auth::user();
                                                                if ($user->hasRole('super_admin')) {
                                                                    return "http://10.2.0.170:8090/admin/pagos/{$record->pago_id}";
                                                                }
                                                                if ($user->hasRole('vendedor')) {
                                                                    return "http://10.2.0.170:8090/vendedor/pagos/{$record->pago_id}";
                                                                }
                                                                return '#';
                                                            })
                                                            ->openUrlInNewTab()
                                                            ->color('info'),
                                                        
                                                        TextEntry::make('fecha_abono')
                                                            ->label('Fecha del Abono')
                                                            ->date('d/m/Y'),

                                                        TextEntry::make('monto')
                                                            ->label('Monto Abonado')
                                                            ->formatStateUsing(fn (float $state): string => '$' . number_format($state, 2)),
                                                        
                                                        TextEntry::make('user.name')
                                                            ->label('Registrado por')
                                                            ->placeholder('N/A'),
                                                    ])
                                            ])
                                    ])
                            ]),
                    ]),
            ]);
    }
}