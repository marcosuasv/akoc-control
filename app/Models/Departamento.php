<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Departamento extends Model
{
    use HasFactory;

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class);
    }
    protected $fillable = [
        'desarrollo_id',
        'numero',
        'piso',
        'modelo',
        'precio',
        'm2_construccion',
        'm2_terraza',
        'recamaras',
        'banos',
        'estacionamientos',
        'estatus',
        'galeria',
    ];


    protected $casts = [
        'galeria' => 'array',
    ];

    public function desarrollo(): BelongsTo
    {
        return $this->belongsTo(Desarrollo::class);
    }
    public function venta(): HasOne
    {
        return $this->hasOne(Venta::class);
    }
}