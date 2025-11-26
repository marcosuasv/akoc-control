<?php

namespace App\Filament\Admin\Resources\Pagos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use App\Models\Pago;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;
use App\Filament\Admin\Resources\Abonos\AbonoResource;
use Illuminate\Support\Facades\Auth;

class PagoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Grid::make(1)
                    ->columnSpan(2)
                    ->schema([
                        Section::make('Información del Pago')
                            ->icon('heroicon-s-banknotes')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('cantidad_general')
                                    ->label('Monto del Pago')
                                    ->money('MXN')
                                    ->icon('heroicon-s-currency-dollar')
                                    ->size('lg'),
                                TextEntry::make('fecha')
                                    ->label('Fecha de Pago')
                                    ->date('d/m/Y')
                                    ->icon('heroicon-s-calendar-days'),
                                TextEntry::make('metodo_pago')
                                    ->label('Método de Pago')
                                    ->icon('heroicon-s-credit-card')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                                    ->color(fn(string $state): string => match ($state) {
                                        'transferencia' => 'primary',
                                        'efectivo' => 'success',
                                        'tarjeta' => 'warning',
                                        'cheque' => 'info',
                                        default => 'gray',
                                    }),
                                TextEntry::make('referencia')
                                    ->label('Referencia / Folio')
                                    ->icon('heroicon-s-clipboard-document')
                                    ->placeholder('N/A')
                                    ->copyable(),
                                TextEntry::make('comentarios')
                                    ->label('Comentarios')
                                    ->placeholder('Sin comentarios.')
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Estado y Adjuntos')
                            ->icon('heroicon-s-check-circle')
                            ->columns(2)
                            ->schema([
                                IconEntry::make('validacion')
                                    ->label('Pago Validado')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('success')
                                    ->falseColor('danger'),
                                TextEntry::make('adjuntar_archivo')
                                    ->label('Comprobante Adjunto')
                                    ->icon('heroicon-s-paper-clip')
                                    ->formatStateUsing(fn(?string $state) => $state ? 'Ver/Descargar Comprobante' : 'Sin adjunto')
                                    ->url(function (Pago $record): ?string {
                                        if (empty($record->adjuntar_archivo)) {
                                            return null;
                                        }
                                        $filename = basename($record->adjuntar_archivo);
                                        return URL::route('ver-comprobante', ['filename' => $filename]);
                                    })
                                    ->openUrlInNewTab()
                                    ->color('primary')
                                    ->placeholder('Sin adjunto'),
                            ]),
                    ]),

                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Cliente')
                            ->icon('heroicon-s-user')
                            ->relationship('cliente')
                            ->schema([
                                TextEntry::make('razon_social')
                                    ->label('Razón Social')
                                    ->weight('bold')
                                    ->icon('heroicon-s-building-office')
                                    ->placeholder('Sin Razón Social'),

                                TextEntry::make('rfc')
                                    ->label('RFC')
                                    ->fontFamily('mono')
                                    ->icon('heroicon-s-identification'),

                                TextEntry::make('tipo_persona')
                                    ->label('Tipo')
                                    ->badge()
                                    ->formatStateUsing(fn (string $state): string => $state === 'moral' ? 'Moral' : 'Física')
                                    ->color(fn (string $state): string => $state === 'moral' ? 'info' : 'success'),

                                TextEntry::make('contacto')
                                    ->label('Persona de Contacto')
                                    ->getStateUsing(fn($record) => "{$record->nombre} {$record->apellidos}")
                                    ->icon('heroicon-s-user'),

                                TextEntry::make('correo')
                                    ->label('Correo')
                                    ->icon('heroicon-s-envelope')
                                    ->copyable()
                                    ->placeholder('N/A'),

                                TextEntry::make('telefono')
                                    ->label('Teléfono')
                                    ->icon('heroicon-s-phone')
                                    ->copyable()
                                    ->placeholder('N/A'),
                                    
                                TextEntry::make('direccion')
                                    ->label('Dirección')
                                    ->icon('heroicon-s-map-pin')
                                    ->placeholder('N/A'),
                            ]),
                        Section::make('Historial')
                            ->icon('heroicon-s-clock')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Fecha de Creación')
                                    ->dateTime()
                                    ->placeholder('-'),
                                TextEntry::make('updated_at')
                                    ->label('Última Actualización')
                                    ->dateTime()
                                    ->placeholder('-'),
                            ]),
                    ]),

                Section::make('Detalles Adicionales y Abonos Aplicados')
                    ->icon('heroicon-s-arrows-right-left')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('cantidad_general')
                                    ->label('Monto Total del Pago')
                                    ->money('MXN')
                                    ->size('lg'),
                                TextEntry::make('montoAplicado')
                                    ->label('Monto Aplicado (Abonos)')
                                    ->money('MXN')
                                    ->size('lg')
                                    ->color('success'),
                                TextEntry::make('saldoRestante')
                                    ->label('Saldo Restante por Aplicar')
                                    ->money('MXN')
                                    ->size('lg')
                                    ->icon(fn($state) => $state > 0 ? 'heroicon-s-exclamation-triangle' : 'heroicon-s-check-badge')
                                    ->color(fn($state) => $state > 0 ? 'warning' : 'success'),
                            ])
                            ->columnSpanFull(),

                        RepeatableEntry::make('abonos')
                            ->label('Listado de Abonos Realizados con este Pago')
                            ->columnSpanFull()
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextEntry::make('id')
                                            ->label('ID Abono')
                                            ->url(function (Model $record): string {
                                                $user = Auth::user();
                                                if ($user->hasRole('super_admin')) {
                                                    return "http://10.2.0.170:8090/admin/abonos/{$record->id}";
                                                }
                                                if ($user->hasRole('vendedor')) {
                                                    return "http://10.2.0.170:8090/vendedor/abonos/{$record->id}";
                                                }
                                                return '#';
                                            })
                                            ->openUrlInNewTab()
                                            ->icon('heroicon-s-identification')
                                            ->color('info')
                                            ->weight('bold'),

                                        TextEntry::make('fecha_abono')
                                            ->label('Fecha del Abono')
                                            ->date('d/m/Y')
                                            ->icon('heroicon-s-calendar'),

                                        TextEntry::make('monto')
                                            ->label('Monto Abonado')
                                            ->money('MXN')
                                            ->weight('bold')
                                            ->color('success'),
                                        
                                        TextEntry::make('planPago.id')
                                            ->label('Aplicado a')
                                            ->icon('heroicon-s-document-check')
                                            ->placeholder('N/A')
                                            ->formatStateUsing(function (Model $record): string {
                                                if ($plan = $record->planPago) {
                                                    return "Cuota #{$plan->numero_pago} (Venta ID: {$plan->venta_id})";
                                                }
                                                return 'N/A';
                                            })
                                            ->url(function (Model $record): string {
                                                $user = Auth::user();
                                                $ventaId = $record->planPago?->venta_id;
                                                if (!$ventaId) return '#';

                                                if ($user->hasRole('super_admin')) {
                                                    return "http://10.2.0.170:8090/admin/ventas/{$ventaId}";
                                                }
                                                if ($user->hasRole('vendedor')) {
                                                    return "http://10.2.0.170:8090/vendedor/ventas/{$ventaId}";
                                                }
                                                return '#';
                                            })
                                            ->openUrlInNewTab()
                                            ->color('primary'),

                                        TextEntry::make('user.name')
                                            ->label('Registrado Por')
                                            ->icon('heroicon-s-pencil-square')
                                            ->placeholder('Sistema'),

                                        TextEntry::make('comentarios')
                                            ->label('Comentarios del Abono')
                                            ->placeholder('Sin comentarios.')
                                            ->columnSpanFull(),
                                    ])
                                    ->extraAttributes(['class' => 'space-y-4']),
                            ]),
                    ]),
            ]);
    }
}