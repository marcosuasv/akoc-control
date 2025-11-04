<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Abono extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'monto',          
        'venta_id',     
        'user_id',        
        'pago_id',
        'fecha_abono',   
        'comentarios',
        'plan_pago_id'    
    ];


    public function pago():BelongsTo
    {
        return $this->belongsTo(Pago::class);
    }

    public function venta():BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function planPago(): BelongsTo
    {
        // Apunta al modelo PlanPago
        return $this->belongsTo(PlanPago::class);
    }
}