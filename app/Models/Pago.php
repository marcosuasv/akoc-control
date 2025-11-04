<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // <-- ¡MUY IMPORTANTE!

class Pago extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Si prefieres $fillable en lugar de $guarded:
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'cliente_id',
    //     'cantidad_general',
    //     'fecha',
    //     'metodo_pago',
    //     'referencia',
    //     'adjuntar_archivo',
    //     'comentarios',
    //     'validacion',
    // ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = []; // Más fácil para empezar

    /**
     * Define los casts para los atributos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha' => 'date',
        'validacion' => 'boolean',
        'cantidad_general' => 'decimal:2',
    ];

   
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    

    public function abonos()
    {
        return $this->hasMany(Abono::class);
    }

   
    public function montoAplicado(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->abonos()->sum('monto'),
        );
    }


    public function saldoRestante(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cantidad_general - $this->montoAplicado,
        );
    }
}