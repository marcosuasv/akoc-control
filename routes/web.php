<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\EstadoDeCuentaController;

// Redirige la raíz a tu login central
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas para tu sistema de login centralizado
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

// Otras rutas de tu aplicación
Route::post('/generar-cotizacion-pdf', [CotizacionController::class, 'generarPdf'])->name('cotizacion.pdf');
Route::get('/ver-comprobante/{filename}', function ($filename) {
    $path = storage_path('app/private/comprobantes_pago/' . $filename);

    abort_unless(file_exists($path), 404);

    // Opcional: proteger con login
    // abort_unless(auth()->check(), 403);

    return response()->file($path);
})->name('ver-comprobante');
Route::get('/ventas/{venta}/estado-de-cuenta', [EstadoDeCuentaController::class, 'show'])
    ->name('ventas.estado-de-cuenta')
    ->middleware('auth');