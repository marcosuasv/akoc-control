<?php

namespace App\Filament\Admin\Resources\Clientes\Schemas;

use App\Models\User;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\FileUpload;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Cliente')
                    ->description('Datos fiscales y de contacto.')
                    ->schema([
                        Hidden::make('tipo_persona')
                            ->default('fisica')
                            ->dehydrated(),

                        TextInput::make('rfc')
                            ->label('RFC')
                            ->placeholder('12 o 13 caracteres')
                            ->required()
                            ->unique(table: 'clientes', ignoreRecord: true)
                            ->maxLength(13)
                            ->minLength(12)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set) {
                                $length = strlen($state);
                                if ($length === 12) {
                                    $set('tipo_persona', 'moral');
                                } elseif ($length === 13) {
                                    $set('tipo_persona', 'fisica');
                                }
                            })
                            ->helperText('El sistema detectará si es Física o Moral.'),

                        TextInput::make('razon_social')
                            ->label('Razón Social')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('Nombre legal de la persona física o moral (tal cual aparece en la constancia).'),

                        FileUpload::make('constancia_fiscal')
                            ->label('Constancia de Situación Fiscal')
                            ->directory('constancias-fiscales')
                            ->acceptedFileTypes(['application/pdf'])
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),

                        TextInput::make('nombre')
                            ->label('Nombre de Contacto')
                            ->required()
                            ->placeholder('Quien atiende la cuenta'),

                        TextInput::make('apellidos')
                            ->label('Apellidos de Contacto')
                            ->required(),

                        TextInput::make('ocupacion')
                            ->label('Ocupación / Puesto')
                            ->placeholder('Ej. Gerente de Compras, Dueño, etc.'),

                        DatePicker::make('fecha_de_nacimiento')
                            ->label('Fecha de Nacimiento (Contacto)')
                            ->displayFormat('d/m/Y')
                            ->native(false),

                        TextInput::make('telefono')
                            ->tel()
                            ->required()
                            ->label('Teléfono'),

                        TextInput::make('correo')
                            ->email()
                            ->required()
                            ->label('Correo de Contacto')
                            ->columnSpanFull(),

                        TextInput::make('direccion')
                            ->label('Dirección Fiscal / Entrega')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Acceso al Sistema')
                    ->description('Cuenta de usuario para que el contacto acceda al panel.')
                    ->collapsible()
                    ->schema([
                        Select::make('user_id')
                            ->label('Usuario del Sistema')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Seleccionar o Crear Usuario')
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
                ->required(),
            TextInput::make('email')
                ->label('Email de Acceso')
                ->email()
                ->required()
                ->unique(table: 'users', column: 'email'),
            TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->required()
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->dehydrated(fn($state) => filled($state)),
        ];
    }

    public static function createUser(): \Closure
    {
        return function (array $data): int {
            $user = User::create($data);
            return $user->id;
        };
    }
}