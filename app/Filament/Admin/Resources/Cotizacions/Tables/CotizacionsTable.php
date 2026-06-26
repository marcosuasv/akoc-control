<?php

namespace App\Filament\Admin\Resources\Cotizacions\Tables;

use App\Models\Cotizacion;
use App\Models\Venta;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class CotizacionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Folio')
                    ->formatStateUsing(fn ($state) => '#' . str_pad($state, 5, '0', STR_PAD_LEFT))
                    ->weight('bold')
                    ->sortable(),

                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->label('Prospecto Interesado')
                    ->formatStateUsing(fn ($record) => $record->cliente ? $record->cliente->nombre_completo : 'Sin cliente')
                    ->description(fn ($record) => $record->cliente?->correo)
                    ->searchable(),

                Tables\Columns\TextColumn::make('departamento.numero')
                    ->label('Unidad')
                    ->description(fn ($record) => $record->departamento?->desarrollo?->nombre)
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('precio_departamento')
                    ->label('Valor Total')
                    ->money('MXN')
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('monto_pago_periodico')
                    ->label('Mensualidad')
                    ->money('MXN')
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('estatus')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'borrador' => 'gray',
                        'enviada' => 'warning',
                        'aceptada' => 'success',
                        'vencida' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estatus')
                    ->options([
                        'borrador' => 'Borrador',
                        'enviada' => 'Enviada',
                        'aceptada' => 'Aceptada',
                        'vencida' => 'Vencida',
                    ])->native(false),
            ])
            ->actions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Modificar Cotización'),

                Action::make('descargarPdf')
                    ->label('Imprimir')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('gray')
                    ->iconButton()
                    ->tooltip('Descargar PDF Corporativo')
                    ->action(function (Cotizacion $record) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cotizacion', [
                            'cotizacion' => $record
                        ]);
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, "Cotizacion_Folio_{$record->id}.pdf");
                    }),

                Action::make('convertirAVenta')
                    ->label('Formalizar Venta')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->iconButton()
                    ->tooltip('Cerrar y Convertir en Venta Real')
                    ->requiresConfirmation()
                    ->modalHeading('Cierre de Operación Comercial')
                    ->modalDescription('¿Estás seguro de formalizar esta cotización? Al hacerlo, se generará el plan definitivo y la unidad pasará a estatus "Vendido".')
                    ->hidden(fn (Cotizacion $record) => $record->estatus !== 'aceptada' || $record->departamento?->estatus === 'vendido')
                    ->action(function (Cotizacion $record) {
                        $venta = Venta::create([
                            'monto_total_venta' => $record->precio_departamento,
                            'enganche' => $record->monto_enganche,
                            'n_pagos' => $record->numero_pagos,
                            'frecuencia_pagos' => $record->frecuencia_pagos,
                            'fecha' => now(),
                            'intereses' => $record->intereses_porcentaje ?? 0, 
                            'departamento_id' => $record->departamento_id,
                            'preciom2' => $record->precio_departamento / ($record->departamento?->m2_construccion ?: 1),
                        ]);

                        $venta->clientes()->attach($record->cliente_id);
                        $record->departamento->update(['estatus' => 'vendido']);

                        Notification::make()
                            ->title('¡Venta Completada con Éxito!')
                            ->body("La unidad {$record->departamento->numero} ha sido asignada a su nuevo dueño.")
                            ->success()
                            ->send();
                    })
            ]);
    }
}
