<?php

namespace App\Filament\Admin\Resources\Abonos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pago;
use Filament\Infolists\Components\IconEntry;
use App\Filament\Admin\Resources\Pagos\PagoResource; // Asumido
use App\Filament\Admin\Resources\Ventas\VentaResource; // Asumido
use App\Filament\Admin\Resources\Departamentos\DepartamentoResource; // Asumido
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class AbonoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                Section::make('Detalles del Abono')
                    ->icon('heroicon-s-currency-dollar')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('monto')
                            ->label('Monto Abonado')
                            ->money('MXN') 
                            ->icon('heroicon-s-banknotes')
                            ->columnSpan(1),

                        TextEntry::make('fecha_abono')
                            ->label('Fecha de Abono')
                            ->date()
                            ->icon('heroicon-s-calendar-days')
                            ->columnSpan(1),
                        
                        TextEntry::make('user.name') 
                            ->label('Registrado por')
                            ->icon('heroicon-s-user')
                            ->placeholder('N/A')
                            ->columnSpan(1),

                        TextEntry::make('comentarios')
                            ->label('Comentarios')
                            ->columnSpanFull()
                            ->placeholder('Sin comentarios.')
                            ->visible(fn ($state) => !empty($state)), 

                        TextEntry::make('created_at')
                            ->label('Fecha de Registro')
                            ->dateTime()
                            ->placeholder('-')
                            ->columnSpan(1),
                        
                        TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime()
                            ->placeholder('-')
                            ->columnSpan(1),
                    ]),

                Section::make('Pago Asociado (Origen del Dinero)')
                    ->icon('heroicon-s-receipt-percent')
                    ->relationship('pago') 
                    ->columns(3)
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID del Pago')
                            ->badge()
                            ->url(function (Model $record): string { // $record es el Pago
                                $user = Auth::user();
                                if ($user->hasRole('super_admin')) {
                                    return "http://10.2.0.170:8090/admin/pagos/{$record->id}";
                                }
                                if ($user->hasRole('vendedor')) {
                                    return "http://10.2.0.170:8090/vendedor/pagos/{$record->id}";
                                }
                                return '#';
                            })
                            ->openUrlInNewTab()
                            ->color('info'),

                        TextEntry::make('cliente.nombre_completo')
                            ->label('Cliente del Pago')
                            ->icon('heroicon-s-user')
                            ->columnSpan(2),

                        TextEntry::make('fecha')
                            ->label('Fecha de Emisión del Pago')
                            ->date(),

                        TextEntry::make('cantidad_general')
                            ->label('Monto Total del Pago')
                            ->money('MXN'),
                        
                        TextEntry::make('metodo_pago')
                            ->label('Método de Pago')
                            ->badge(),

                        TextEntry::make('referencia')
                            ->label('Referencia')
                            ->copyable()
                            ->placeholder('N/A')
                            ->columnSpan(2),
                        
                        IconEntry::make('validacion')
                            ->label('Pago Validado')
                            ->boolean(), 
                    ]),

                // --- 👇 SECCIÓN CORREGIDA Y MEJORADA 👇 ---
                
                Section::make('Cuota y Venta Aplicada (Destino del Dinero)')
                    ->icon('heroicon-s-document-check')
                    ->relationship('planPago') // La relación correcta
                    ->columns(3)
                    ->schema([
                        TextEntry::make('numero_pago')
                            ->label('Cuota Aplicada #')
                            ->badge()
                            ->columnSpan(1),

                        TextEntry::make('monto')
                            ->label('Monto Total de la Cuota')
                            ->money('MXN')
                            ->columnSpan(1),
                        
                        TextEntry::make('saldo') // Usa el Accessor del modelo PlanPago
                            ->label('Saldo Restante de la Cuota')
                            ->money('MXN')
                            ->color('warning')
                            ->columnSpan(1),

                        TextEntry::make('fecha_vencimiento')
                            ->label('Vencimiento de Cuota')
                            ->date()
                            ->columnSpan(1),

                        // Link a la Venta (a través de planPago.venta)
                        TextEntry::make('venta.id')
                            ->label('ID Venta')
                            ->badge()
                            ->color('primary')
                            ->url(function (Model $record): string { // $record es la Venta
                                $user = Auth::user();
                                if ($user->hasRole('super_admin')) {
                                    return "http://10.2.0.170:8090/admin/ventas/{$record->id}";
                                }
                                if ($user->hasRole('vendedor')) {
                                    return "http://10.2.0.170:8090/vendedor/ventas/{$record->id}";
                                }
                                return '#';
                            })
                            ->openUrlInNewTab()
                            ->columnSpan(1),

                        // Link al Departamento (a través de planPago.venta.departamento)
                        TextEntry::make('venta.departamento.numero')
                            ->label('Departamento')
                            ->badge()
                            ->color('info')
                            ->url(function (Model $record): string { // $record es el Departamento
                                $user = Auth::user();
                                if ($user->hasRole('super_admin')) {
                                    return "http://10.2.0.170:8090/admin/departamentos/{$record->id}";
                                }
                                if ($user->hasRole('vendedor')) {
                                    return "http://10.2.0.170:8090/vendedor/departamentos/{$record->id}";
                                }
                                return '#';
                            })
                            ->openUrlInNewTab()
                            ->columnSpan(1),
                    ]),
            ]);
    }
}