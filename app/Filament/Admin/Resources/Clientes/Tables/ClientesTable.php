<?php

namespace App\Filament\Admin\Resources\Clientes\Tables;

use App\Models\Cliente;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;

class ClientesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Columna de Nombre Completo (más intuitiva)
                TextColumn::make('nombre')
                    ->label('Nombre Completo')
                    ->description(fn (Cliente $record): string => $record->apellidos)
                    ->searchable(['nombre', 'apellidos']) // Busca en ambos campos
                    ->sortable(),

                // 2. Columna de Contacto con Iconos y Acciones
                TextColumn::make('correo')
                    ->label('Contacto')
                    ->icon('heroicon-s-envelope')
                    ->copyable() // Permite copiar el correo
                    ->description(fn (Cliente $record): string => $record->telefono ?? 'Sin teléfono')
                    ->searchable(['correo', 'telefono']),

                // 3. Columna Visual para el Acceso al Sistema
                IconColumn::make('user_id')
                    ->label('Acceso al Sistema')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn (Cliente $record): string => $record->user_id ? 'Tiene acceso' : 'No tiene acceso'),
                
                // 4. Fechas más legibles y ocultas por defecto
                TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->since() // Muestra "hace 2 días"
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Oculta por defecto

                TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // 5. Filtro Rápido: ¿Tiene acceso al sistema? (Sí / No / Todos)
                TernaryFilter::make('user_id')
                    ->label('Acceso al Sistema')
                    ->boolean()
                    ->trueLabel('Con Acceso')
                    ->falseLabel('Sin Acceso')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('user_id'),
                        false: fn (Builder $query) => $query->whereNull('user_id'),
                    ),

                // 6. Filtro por Fecha de Creación
                Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')->label('Creado desde'),
                        \Filament\Forms\Components\DatePicker::make('created_until')->label('Creado hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                // 7. Acciones de Fila Agrupadas para una UI más limpia
                ActionGroup::make([
                    ViewAction::make()->icon('heroicon-s-eye'),
                    EditAction::make()->icon('heroicon-s-pencil'),
                    DeleteAction::make()->icon('heroicon-s-trash'),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}