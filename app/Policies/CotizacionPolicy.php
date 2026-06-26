<?php

namespace App\Policies;

use App\Models\Cotizacion;
use App\Models\User;

class CotizacionPolicy
{
    /**
     * Determina si el usuario puede ver el listado en el menú.
     */
    public function viewAny(User $user): bool
    {
        return true; // Cambiar a true para que aparezca en el menú lateral
    }

    /**
     * Determina si el usuario puede ver el detalle de una cotización.
     */
    public function view(User $user, Cotizacion $cotizacion): bool
    {
        return true;
    }

    /**
     * Determina si el asesor puede crear nuevas cotizaciones.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determina si pueden editarse las cotizaciones.
     */
    public function update(User $user, Cotizacion $cotizacion): bool
    {
        return true;
    }
}