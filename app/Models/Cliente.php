<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class);
    }
    protected $fillable = [
        'nombre',
        'apellidos',
        'direccion',
        'telefono',
        'correo',
        'user_id',
        'ocupacion',
        'fecha_de_nacimiento',
        'tipo_persona',
        'rfc',
        'razon_social',
        'constancia_fiscal',
    ];

    protected $casts = [
        'fecha_de_nacimiento' => 'date',
    ];

    public function ventas(): BelongsToMany
    {
        return $this->belongsToMany(Venta::class, 'cliente_venta');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellidos}";
    }
}