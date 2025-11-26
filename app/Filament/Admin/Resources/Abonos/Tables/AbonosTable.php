<?php

namespace App\Filament\Admin\Resources\Abonos\Tables;

use Filament\Tables\Table;
use App\Models\Abono;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AbonosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups(['planPago.venta.departamento.numero'])
            ->defaultGroup('planPago.venta.departamento.numero')
            ->columns([
                TextColumn::make('monto')
                    ->label('Monto Abono')
                    ->money('mxn')
                    ->sortable(),

                TextColumn::make('fecha_abono')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('pago.cliente.razon_social')
                    ->label('Cliente')
                    ->sortable()
                    ->getStateUsing(fn(Abono $record) => $record->pago->cliente->razon_social ?? "{$record->pago->cliente->nombre} {$record->pago->cliente->apellidos}")
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('pago.cliente', function (Builder $q) use ($search) {
                            $q->where('razon_social', 'like', "%{$search}%")
                              ->orWhere(DB::raw("CONCAT(nombre, ' ', apellidos)"), 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('planPago.venta.departamento.numero')
                    ->label('Departamento')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('planPago.venta.departamento', function (Builder $q) use ($search) {
                            $q->where('numero', 'like', "%{$search}%");
                        });
                    }),
                
                TextColumn::make('planPago.numero_pago')
                    ->label('Cuota #')
                    ->badge()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Registró')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('user', function (Builder $q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('comentarios')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('fecha_abono')
                    ->label('Rango de Fechas')
                    ->form([
                        DatePicker::make('fecha_desde')->label('Desde'),
                        DatePicker::make('fecha_hasta')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_desde'],
                                fn(Builder $query, $date) => $query->whereDate('abonos.fecha_abono', '>=', $date)
                            )
                            ->when(
                                $data['fecha_hasta'],
                                fn(Builder $query, $date) => $query->whereDate('abonos.fecha_abono', '<=', $date)
                            );
                    }),
                SelectFilter::make('user')
                    ->label('Registrado por')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
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
            ->defaultSort('fecha_abono', 'desc');
    }
}