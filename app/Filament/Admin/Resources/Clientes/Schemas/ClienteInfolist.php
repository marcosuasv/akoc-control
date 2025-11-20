<?php

namespace App\Filament\Admin\Resources\Clientes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class ClienteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Cliente')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('nombre'),
                                TextEntry::make('apellidos'),
                                
                                TextEntry::make('ocupacion')
                                    ->label('Ocupación')
                                    ->placeholder('No registrada'),
                                    
                                TextEntry::make('fecha_de_nacimiento')
                                    ->label('Fecha de Nacimiento')
                                    ->date()
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
                            ->placeholder('Sin acceso al sistema'),
                        
                        TextEntry::make('created_at')
                            ->label('Fecha de Creación')
                            ->dateTime()
                            ->icon('heroicon-s-calendar-days')
                            ->placeholder('-'),
                        
                        TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime()
                            ->icon('heroicon-s-clock')
                            ->placeholder('-'),
                    ]),
            ]);
    }
}