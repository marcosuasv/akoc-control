<?php

namespace App\Filament\Admin\Resources\Departamentos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use App\Models\Departamento;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Desarrollo;
use Illuminate\Database\Eloquent\Builder;

class DepartamentosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero')
                    ->label('Depto.')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Departamento $record): string => "Piso {$record->piso} / Mod. {$record->modelo}"),

                TextColumn::make('estatus')
                    ->label('Estatus')
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

                TextColumn::make('venta.monto_total_venta')
                    ->label('Costo Venta')
                    ->default(fn(Departamento $record) => $record->precio)
                    ->formatStateUsing(fn(float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                    ->sortable(),

                TextColumn::make('abonos_acumulados')
                    ->label('Abonado')
                    ->getStateUsing(function (Departamento $record): ?float {
                        if (is_null($record->venta)) {
                            return 0.0;
                        }

                        return $record->venta->abonos()->sum('abonos.monto') ?? 0.0; // <-- ¡Corregido!
                    })
                    ->formatStateUsing(fn(float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('saldo_pendiente')
                    ->label('Saldo Pendiente')
                    ->getStateUsing(function (Departamento $record): ?float {
                        $costo = $record->precio ?? 0.0;
                        $abonado = 0.0;
                        if (!is_null($record->venta)) {
                            $costo = $record->venta->monto_total_venta ?? $record->precio ?? 0.0;
                            $abonado = $record->venta->abonos()->sum('abonos.monto') ?? 0.0;
                        }return $costo - $abonado;
                    })
                    ->formatStateUsing(fn(float $state): string => '$' . number_format($state, 2, '.', ',') . ' MXN')
                    ->color(function (float $state): string {
                        return $state > 0 ? 'warning' : 'success';
                    })
                    ->sortable(),

                TextColumn::make('recamaras')
                    ->label('Recs.')
                    ->alignCenter(),

                TextColumn::make('estacionamientos')
                    ->label('Estacs.')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('desarrollo_id')
                    ->label('Desarrollo')
                    ->relationship('desarrollo', 'nombre')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('piso')
                    ->label('Piso')
                    // Aquí la lógica debe ser estática para el SelectFilter sin depender de filtros reactivos en este nivel.
                    // Utilizamos la consulta base de todos los pisos distintos para asegurar la carga.
                    ->options(Departamento::query()->distinct()->pluck('piso', 'piso'))
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
