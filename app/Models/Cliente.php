<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellidos',
        'direccion',
        'telefono',
        'correo',
        'user_id',
    ];

    /**
     * Define la relación de muchos a muchos con Venta.
     * Un cliente puede estar asociado a una o varias ventas.
     */
    public function ventas(): BelongsToMany
    {
        // El segundo argumento es el nombre de la tabla pivote.
        return $this->belongsToMany(Venta::class, 'cliente_venta');
    }

    /**
     * Define la relación inversa de uno a muchos con User.
     * Un cliente puede estar asociado a un usuario del sistema.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellidos}";
    }
}