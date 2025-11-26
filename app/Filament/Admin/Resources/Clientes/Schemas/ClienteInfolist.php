<?php

namespace App\Filament\Admin\Resources\Clientes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Storage;

class ClienteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación Fiscal')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('tipo_persona')
                                    ->label('Tipo')
                                    ->badge()
                                    ->formatStateUsing(fn (string $state): string => $state === 'moral' ? 'Moral (Empresa)' : 'Física')
                                    ->color(fn (string $state): string => $state === 'moral' ? 'info' : 'success')
                                    ->icon(fn (string $state): string => $state === 'moral' ? 'heroicon-s-building-office' : 'heroicon-s-user'),

                                TextEntry::make('rfc')
                                    ->label('RFC')
                                    ->weight('bold')
                                    ->fontFamily('mono')
                                    ->copyable()
                                    ->icon('heroicon-s-identification'),

                                TextEntry::make('razon_social')
                                    ->label('Razón Social')
                                    ->columnSpan(2)
                                    ->placeholder('La razón social coincide con el nombre.'),

                                TextEntry::make('constancia_fiscal')
                                    ->label('Constancia Fiscal')
                                    ->badge()
                                    ->color('primary')
                                    ->formatStateUsing(fn () => 'Descargar Documento')
                                    ->icon('heroicon-s-document-arrow-down')
                                    ->url(fn ($record) => $record->constancia_fiscal ? Storage::url($record->constancia_fiscal) : null)
                                    ->openUrlInNewTab()
                                    ->visible(fn ($record) => !empty($record->constancia_fiscal)),
                            ]),
                    ]),

                Section::make('Información del Representante / Cliente')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('nombre')
                                    ->label('Nombre'),
                                TextEntry::make('apellidos')
                                    ->label('Apellidos'),
                                
                                TextEntry::make('ocupacion')
                                    ->label('Ocupación / Puesto')
                                    ->placeholder('No registrada'),
                                    
                                TextEntry::make('fecha_de_nacimiento')
                                    ->label('Fecha de Nacimiento')
                                    ->date('d/m/Y')
                                    ->placeholder('No registrada'),
                            ]),
                    ]),

                Section::make('Información de Contacto')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('telefono')
                            ->label('Teléfono')
                            ->icon('heroicon-s-phone')
                            ->copyable()
                            ->url(fn (string $state): string => "tel:{$state}")
                            ->placeholder('No registrado'),
                        
                        TextEntry::make('correo')
                            ->label('Correo de Contacto')
                            ->icon('heroicon-s-envelope')
                            ->copyable()
                            ->url(fn (string $state): string => "mailto:{$state}")
                            ->placeholder('No registrado'),
                        
                        TextEntry::make('direccion')
                            ->label('Dirección')
                            ->icon('heroicon-s-map-pin')
                            ->columnSpanFull()
                            ->placeholder('No registrada'),
                    ]),
                
                Section::make('Datos del Sistema')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Usuario de Acceso')
                            ->icon('heroicon-s-user')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Sin acceso al sistema'),
                        
                        TextEntry::make('created_at')
                            ->label('Fecha de Registro')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-s-calendar-days')
                            ->placeholder('-'),
                        
                        TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-s-clock')
                            ->placeholder('-'),
                    ]),
            ]);
    }
}