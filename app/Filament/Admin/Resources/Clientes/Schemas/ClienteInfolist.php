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
                // GRUPO 1: INFORMACIÓN PRINCIPAL
                Section::make('Información del Cliente')
                    ->schema([
                        // Usamos un Grid para poner nombre y apellidos juntos
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('nombre'),
                                TextEntry::make('apellidos'),
                            ]),
                    ]),

                // GRUPO 2: DATOS DE CONTACTO
                Section::make('Información de Contacto')
                    ->columns(2) // Organizamos esta sección en 2 columnas
                    ->schema([
                        TextEntry::make('telefono')
                            ->label('Teléfono')
                            ->icon('heroicon-s-phone') // <-- Icono
                            ->copyable() // <-- Acción: Copiar al portapapeles
                            ->placeholder('No registrado'),
                        
                        TextEntry::make('correo')
                            ->label('Correo de Contacto')
                            ->icon('heroicon-s-envelope') // <-- Icono
                            ->copyable() // <-- Acción: Copiar
                            ->url(fn (string $state): string => "mailto:{$state}") // <-- Acción: Abrir email
                            ->placeholder('No registrado'),
                        
                        TextEntry::make('direccion')
                            ->label('Dirección')
                            ->icon('heroicon-s-map-pin') // <-- Icono
                            ->columnSpanFull() // <-- Ocupa todo el ancho
                            ->placeholder('No registrada'),
                    ]),
                
                // GRUPO 3: INFORMACIÓN DEL SISTEMA (METADATOS)
                Section::make('Datos del Sistema')
                    ->columns(3) // 3 columnas para esta información
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Usuario de Acceso')
                            ->icon('heroicon-s-user') // <-- Icono
                            ->badge() // <-- Se ve mejor como una "etiqueta"
                            ->placeholder('Sin acceso al sistema'),
                        
                        TextEntry::make('created_at')
                            ->label('Fecha de Creación')
                            ->dateTime()
                            ->icon('heroicon-s-calendar-days') // <-- Icono
                            ->placeholder('-'),
                        
                        TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime()
                            ->icon('heroicon-s-clock') // <-- Icono
                            ->placeholder('-'),
                    ]),
            ]);
    }
}