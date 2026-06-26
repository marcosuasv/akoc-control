<?php

namespace App\Filament\Admin\Resources\Departamentos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Grid;

class DepartamentoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Principal')
                    ->columns(2)
                    ->schema([
                        Select::make('desarrollo_id')
                            ->relationship('desarrollo', 'nombre') // Relaciona con el modelo Desarrollo
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Desarrollo'),

                        Select::make('estatus')
                            ->options([
                                'disponible' => 'Disponible',
                                'apartado' => 'Apartado',
                                'vendido' => 'Vendido',
                            ])
                            ->required()
                            ->native(false), 
                    ]),

                Section::make('Detalles de la Unidad')
                    ->columns(3)
                    ->schema([
                        TextInput::make('numero')
                            ->required()
                             ->unique(table: 'departamentos', ignoreRecord: true),
                        TextInput::make('piso')
                            ->required(),
                        TextInput::make('modelo')
                            ->required(),
                    ]),
                
                Section::make('Características y Precio')
                    ->columns(2)
                    ->schema([
                        TextInput::make('precio')
                            ->required()
                            ->numeric()
                            ->prefix('$'),

                        TextInput::make('m2_construccion')
                            ->label(' M² (Construcción Total)')
                            ->numeric()
                            ->required(),
                            
                        TextInput::make('m2_terraza')
                            ->label('Precio por  M²')
                            ->numeric()
                            ->default(0),

                        TextInput::make('recamaras')
                            ->label('Recámaras')
                            ->integer() 
                            ->required(),

                        TextInput::make('banos')
                            ->label('Baños')
                            ->numeric()
                            ->required(),

                        TextInput::make('estacionamientos')
                            ->label('Estacionamientos')
                            ->integer()
                            ->required(),
                    ]),

             
                 Section::make('Galería de Imágenes')
                    ->schema([
                        FileUpload::make('galeria')
                            ->multiple()       
                            ->directory('private/departamentos-galeria') 
                            ->image()         
                            ->reorderable()    
                            ->imageEditor()   
                            ->maxSize(2048),   
                    ]), 
                     
            ]);
  
    }
}