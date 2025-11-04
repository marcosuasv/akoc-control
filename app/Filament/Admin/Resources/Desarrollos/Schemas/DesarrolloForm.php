<?php

namespace App\Filament\Admin\Resources\Desarrollos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Select;

class DesarrolloForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Desarrollo')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nombre')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('direccion')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('descripcion')
                            ->columnSpanFull(),

                        TagsInput::make('amenidades')
                            ->label('Amenidades (selecciona o escribe)')
                            ->suggestions([
                                'Seguridad 24/7',
                                'Gimnasio',
                                'Alberca',
                                'Roof Garden / Asadores',
                                'Coworking',
                                'Salón de Usos Múltiples',
                                'Ludoteca / Kids Club',
                                'Pet Friendly / Pet Park',
                                'Áreas Verdes',
                                'Estacionamiento',
                            ])
                            ->columnSpanFull(),

                        TextInput::make('total_unidades')
                            ->label('Total de Unidades')
                            ->required()
                            ->numeric(),

                        Select::make('estatus')
                            ->options([
                                'preventa' => 'Preventa',
                                'en_construccion' => 'En Construcción',
                                'entrega_inmediata' => 'Entrega Inmediata',
                            ])
                            ->required()
                            ->native(false),
                    ])
            ]);
    }
}
