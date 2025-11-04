<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsCliente
{
     public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isCliente()) {
            return $next($request);
        }
        // Si no es cliente, redirige o muestra un error 403
        abort(403, 'Acceso no autorizado.');
    }
}
