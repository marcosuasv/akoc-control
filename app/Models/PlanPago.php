<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PlanPago extends Model
{
    use HasFactory;
    
    // Especifica el nombre de la tabla si no sigue la convención de Laravel.
    protected $table = 'plan_pagos';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'venta_id',
        'numero_pago',
        'monto',
        'fecha_vencimiento',
        'status',
        'fecha_pago',
    ];
    
    /**
     * Define la relación inversa con Venta.
     * Cada registro de pago pertenece a una única venta.
     */
    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }
    public function abonos(): HasMany
    {
        // Un PlanPago (cuota) puede tener muchos Abonos
        return $this->hasMany(Abono::class);
    }
    public function montoAbonado(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->abonos()->sum('monto'),
        );
    }
    public function saldo(): Attribute
    {
        return Attribute::make(
            // Es el Monto total de la cuota Menos lo que se ha abonado
            get: fn() => $this->monto - $this->montoAbonado,
        );
    }
    public function getEstadoActualAttribute(): string
    {
        if ($this->montoAbonado <= 0) {
            return 'pendiente';
        }
        if ($this->montoAbonado >= $this->monto) {
            return 'pagado';
        }
        return 'parcial'; // Se ha pagado algo, pero no todo
    }
}