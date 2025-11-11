<?php

namespace App\Filament\Widgets;

use App\Models\PlanPago;
use App\Models\Cliente;
use App\Models\Desarrollo;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Carbon\Carbon;
use Filament\Forms; 
use Filament\Actions\Action;
use App\Filament\Admin\Resources\Ventas\VentaResource;
use App\Filament\Admin\Resources\Pagos\PagoResource;

class ListadoCobranzaWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
           ->query(function () {
            return PlanPago::query()
                ->with([
                    'venta.clientes', 
                    'venta.departamento.desarrollo' 
                ])
                ->withSum('abonos', 'monto') 
                ->groupBy('plan_pagos.id')
                ->havingRaw('monto > COALESCE(abonos_sum_monto, 0)');
        })
            ->columns([
                Tables\Columns\TextColumn::make('venta.clientes.nombre_completo')
                    ->label('Cliente')
                    ->searchable(),

                Tables\Columns\TextColumn::make('venta.departamento.desarrollo.nombre')
                    ->label('Proyecto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venta.departamento.numero')
                    ->label('Unidad')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_vencimiento')
                    ->label('Vencimiento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn (PlanPago $record) => $record->fecha_vencimiento->isPast() ? 'danger' : null),

                Tables\Columns\TextColumn::make('monto')
                    ->label('Monto Cuota')
                    ->money('MXN'), 
                Tables\Columns\TextColumn::make('montoAbonado')
                    ->label('Abonado')
                    ->money('MXN'),
                Tables\Columns\TextColumn::make('saldo')
                    ->label('Saldo Pendiente')
                    ->money('MXN')
                    ->weight('bold')
                    ->color('danger'),
                
                Tables\Columns\TextColumn::make('estadoActual')
                    ->label('Estatus')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'danger',
                        'parcial' => 'warning',
                        'pagado' => 'success',
                    }),
            ])
            ->filters([
                SelectFilter::make('cliente')
                    ->label('Cliente')
                    ->relationship('venta.clientes', 'nombre')
                    ->searchable()
                    ->preload(),
                    
               SelectFilter::make('desarrollo')
                    ->label('Proyecto')
                    ->relationship('venta.departamento.desarrollo', 'nombre')
                    ->searchable()
                    ->preload(),

                Filter::make('vencidos')
                    ->label('Solo Vencidos')
                    ->query(fn (Builder $query): Builder => $query->where('fecha_vencimiento', '<', now())),
                
                Filter::make('proximos_vencer')
                    ->label('Próximos a Vencer (15 días)')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereBetween('fecha_vencimiento', [now(), now()->addDays(15)])
                    ),
            ])
            ->actions([
               Action::make('verVenta')
                    ->label('Ver Venta')
                   ->url(fn (PlanPago $record) => VentaResource::getUrl('view', ['record' => $record->venta_id]))
                    ->icon('heroicon-o-eye')
                    ->color('gray'),
                    
                Action::make('registrarAbono')
                ->label('Registrar Depósito') // Cambié la etiqueta para que sea más claro
                ->icon('heroicon-o-currency-dollar')
                ->url(function (PlanPago $record) {
                   $url = PagoResource::getUrl('create');
                    
                    // 2. Obtiene el cliente de la fila
                    $cliente = $record->venta->clientes->first();

                    // 3. Añade el ID del cliente a la URL generada
                    if ($cliente) {
                        return $url . '?cliente_id=' . $cliente->id;
                    }
                    
                    return $url;
                })
                ->openUrlInNewTab(),
            ]);
    }
}