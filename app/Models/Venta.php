<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'monto_total_venta',
        'enganche',
        'n_pagos',
        'frecuencia_pagos',
        'fecha',
        'intereses',
        'departamento_id',
        'preciom2',
    ];

    public function clientes(): BelongsToMany
    {
        return $this->belongsToMany(Cliente::class, 'cliente_venta');
    }

    public function planPagos(): HasMany
    {
        return $this->hasMany(PlanPago::class);
    }


    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }
    public function abonos(): HasMany
    {
        return $this->hasMany(Abono::class);
    }
}