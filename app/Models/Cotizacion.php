<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cotizaciones';

    protected $fillable = [
        'cliente_id',
        'departamento_id',
        'user_id',
        'precio_departamento',
        'porcentaje_enganche',
        'monto_enganche',
        'numero_pagos',
        'frecuencia_pagos',
        'monto_pago_periodico',
        'intereses_porcentaje',
        'notas',
        'estatus',
        'fecha_vencimiento',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    // Relaciones
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}