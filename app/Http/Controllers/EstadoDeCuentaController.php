<?php

namespace App\Http\Controllers;

use App\Models\Venta; // Asegúrate de importar tu modelo Venta
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EstadoDeCuentaController extends Controller
{
    /**
     * Muestra el PDF del estado de cuenta de una venta.
     */
    public function show(Venta $venta)
    {
        $venta->load([
            'clientes', 
            'departamento.desarrollo', 
            'planPagos.abonos.pago'
        ]);

        $pdf = Pdf::loadView('pdfs.estado-de-cuenta', compact('venta'));

        return $pdf->stream("estado-de-cuenta-venta-{$venta->id}.pdf");
    }
}