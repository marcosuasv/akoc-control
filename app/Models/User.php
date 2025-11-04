<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // <-- 1. IMPORTANTE: Añadir este import
use Filament\Panel;                         // <-- 2. IMPORTANTE: Añadir este import
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;

//          Añadir "implements FilamentUser" aquí  ----v
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Define la relación donde un Usuario puede tener un perfil de Cliente asociado.
     */
    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class);
    }

    /**
     * El método más importante para la seguridad de los paneles de Filament.
     * Determina si el usuario actual puede acceder a un panel específico.
     *
     * @param Panel $panel El panel al que se intenta acceder.
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();

        if ($panelId === 'admin') {
            return $this->hasRole('super_admin');
        }

        if ($panelId === 'vendedor') {
            return $this->hasRole('vendedor');
        }

        if ($panelId === 'cliente') {
            return $this->hasRole('cliente');
        }

        // Por seguridad, si no coincide con ninguno, no se permite el acceso.
        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Atributos y Configuraciones del Modelo
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos de Ayuda (Helpers)
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica si el usuario es administrador.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Verifica si el usuario es un cliente.
     */
    public function isCliente(): bool
    {
        // <-- CORREGIDO: Se usa hasRole() en lugar de comparar una columna.
        return $this->hasRole('cliente');
    }
}