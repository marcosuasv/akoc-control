<?php

namespace App\Filament\Admin\Resources\Pagos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use App\Models\Pago;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class PagosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->leftJoin('clientes', 'pagos.cliente_id', '=', 'clientes.id')
                    ->select('pagos.*');
            })
            ->groups(['cliente_id'])
            ->defaultGroup('cliente_id')
            ->columns([
                TextColumn::make('cliente_display')
                    ->label('Cliente')
                    ->sortable(query: fn($query, $direction) => $query->orderBy('clientes.razon_social', $direction)->orderBy('clientes.nombre', $direction))
                    ->getStateUsing(fn($record) => $record->cliente->razon_social ?? "{$record->cliente->nombre} {$record->cliente->apellidos}")
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $query->where(function ($q) use ($search) {
                            $q->where('clientes.razon_social', 'like', "%{$search}%")
                              ->orWhere(DB::raw("CONCAT(clientes.nombre, ' ', clientes.apellidos)"), 'like', "%{$search}%");
                        });
                        return $query;
                    }),
                TextColumn::make('cantidad_general')
                    ->label('Total Pago')
                    ->money('mxn')
                    ->sortable(),

                TextColumn::make('montoAplicado')
                    ->label('Aplicado')
                    ->money('mxn'),

                TextColumn::make('saldoRestante')
                    ->label('Restante')
                    ->money('mxn')
                    ->badge()
                    ->color(fn(float $state): string => $state > 0 ? 'warning' : 'success'),

                TextColumn::make('fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('metodo_pago')
                    ->searchable()
                    ->badge(),

                IconColumn::make('validacion')
                    ->label('Validado')
                    ->boolean(),

                TextColumn::make('referencia')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('adjuntar_archivo')
                    ->label('Adjunto')
                    ->formatStateUsing(fn() => '')
                    ->icon(fn(?string $state): string => $state ? 'heroicon-o-paper-clip' : '')
                    ->url(function (Pago $record): ?string {
                        if (empty($record->adjuntar_archivo)) {
                            return null;
                        }

                        $filename = basename($record->adjuntar_archivo);

                        return URL::route('ver-comprobante', ['filename' => $filename]);
                    })
                    ->openUrlInNewTab()
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
                SelectFilter::make('cliente_id')
                    ->label('Cliente')
                    ->relationship('cliente', 'razon_social')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->razon_social ?? "{$record->nombre} {$record->apellidos}")
                    ->searchable()
                    ->preload(),
                Filter::make('fecha')
                    ->label('Rango de Fechas')
                    ->form([
                        DatePicker::make('fecha_desde')->label('Desde'),
                        DatePicker::make('fecha_hasta')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_desde'],
                                fn(Builder $query, $date) => $query->whereDate('pagos.fecha', '>=', $date)
                            )
                            ->when(
                                $data['fecha_hasta'],
                                fn(Builder $query, $date) => $query->whereDate('pagos.fecha', '<=', $date)
                            );
                    }),
                SelectFilter::make('metodo_pago')
                    ->label('Método de Pago')
                    ->options([
                        'transferencia' => 'Transferencia',
                        'efectivo' => 'Efectivo',
                        'tarjeta' => 'Tarjeta',
                        'cheque' => 'Cheque',
                        'otro' => 'Otro',
                    ])
                    ->multiple()
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
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