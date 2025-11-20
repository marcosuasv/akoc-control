<?php

namespace App\Filament\Admin\Resources\Clientes\Schemas;

use App\Models\User;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Cliente')
                    ->description('Datos personales y de contacto del cliente.')
                    ->schema([
                        TextInput::make('nombre')
                            ->required(),
                        TextInput::make('apellidos')
                            ->required(),

                        TextInput::make('ocupacion')
                            ->label('Ocupación')
                            ->placeholder('Ej. Doctor, Abogado, etc.'),

                        DatePicker::make('fecha_de_nacimiento')
                            ->label('Fecha de Nacimiento')
                            ->native(false),

                        TextInput::make('telefono')
                            ->tel()
                            ->required()
                            ->label('Teléfono'),
                        TextInput::make('correo')
                            ->email()
                            ->required()
                            ->label('Correo de Contacto')
                            ->unique(table: 'clientes', ignoreRecord: true)
                            ->helperText('Este es el correo principal de contacto (puede ser diferente al de inicio de sesión).'),
                        TextInput::make('direccion')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Acceso al Sistema')
                    ->description('Asigna una cuenta de usuario para que este cliente pueda iniciar sesión en su panel.')
                    ->collapsible()
                    ->schema([
                        Select::make('user_id')
                            ->label('Usuario del Sistema')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Sin acceso al sistema (solo registro de contacto)')
                            ->helperText('Selecciona un usuario existente o crea uno nuevo para el cliente.')
                            ->createOptionForm(static::getUserForm())
                            ->createOptionUsing(static::createUser()),
                    ]),
            ]);
    }

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

    public static function createUser(): \Closure
    {
        return function (array $data): int {
            $user = User::create($data);
            $user->assignRole('cliente');
            return $user->id;
        };
    }
}