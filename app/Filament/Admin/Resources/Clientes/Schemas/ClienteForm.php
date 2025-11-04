<?php

namespace App\Filament\Admin\Resources\Clientes\Schemas;

use App\Models\User; 
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // GRUPO 1: DATOS DEL CLIENTE
                Section::make('Información del Cliente')
                    ->description('Datos personales y de contacto del cliente.')
                    ->schema([
                        TextInput::make('nombre')
                            ->required(),
                        TextInput::make('apellidos')
                            ->required(),
                        TextInput::make('telefono')
                            ->tel()
                            ->required()
                            ->label('Teléfono'),
                        TextInput::make('correo')
                            ->email()
                            ->required()
                            ->label('Correo de Contacto')
                            ->unique(table: 'clientes', ignoreRecord: true) // Evita correos duplicados en clientes
                            ->helperText('Este es el correo principal de contacto (puede ser diferente al de inicio de sesión).'),
                        TextInput::make('direccion')
                            ->required()
                            ->columnSpanFull(), // Ocupa todo el ancho
                    ])
                    ->columns(2), // Organiza este grupo en 2 columnas

                // GRUPO 2: ACCESO AL SISTEMA (PANEL DE CLIENTE)
                Section::make('Acceso al Sistema')
                    ->description('Asigna una cuenta de usuario para que este cliente pueda iniciar sesión en su panel.')
                    ->collapsible() // Se puede colapsar si no se necesita
                    ->schema([
                        Select::make('user_id')
                            ->label('Usuario del Sistema')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Sin acceso al sistema (solo registro de contacto)')
                            ->helperText('Selecciona un usuario existente o crea uno nuevo para el cliente.')
                            
                            // 3. Usa un método helper para definir el formulario (más limpio)
                            ->createOptionForm(static::getUserForm())
                            
                            // 4. Usa un método helper para la lógica de creación
                            ->createOptionUsing(static::createUser()),
                    ]),
            ]);
    }

    /**
     * Define el formulario para crear un nuevo Usuario.
     */
    public static function getUserForm(): array
    {
        return [
            TextInput::make('name')
                ->label('Nombre Completo')
                ->required()
                ->helperText('Este es el nombre que el usuario verá en el sistema (ej. "Juan Pérez").'),
            TextInput::make('email')
                ->label('Email de Acceso')
                ->email()
                ->required()
                ->unique(table: 'users', column: 'email')
                ->helperText('Este email se usará para iniciar sesión.'),
            TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->required()
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->dehydrated(fn($state) => filled($state))
                ->helperText('El usuario usará esta contraseña para iniciar sesión.'),
        ];
    }

    /**
     * Define la lógica personalizada para crear el Usuario.
     * Esto nos permite asignarle el rol de 'cliente' automáticamente.
     */
   public static function createUser(): \Closure
    {
        return function (array $data): int {
            $user = User::create($data);
            $user->assignRole('cliente');
            return $user->id;
        };
    }
}