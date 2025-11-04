<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desarrollo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'descripcion',
        'amenidades',
        'total_unidades',
        'estatus',
    ];


    protected $casts = [
        'amenidades' => 'array',
    ];

    public function departamentos(): HasMany
    {
        return $this->hasMany(Departamento::class);
    }
}