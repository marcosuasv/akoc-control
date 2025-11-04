<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CotizacionController extends Controller
{
    public function generarPdf(Request $request)
    {
        $datos = $request->all();

        // El plan de pagos llega como un string JSON, hay que decodificarlo
        if (isset($datos['planPagos'])) {
            $datos['planPagos'] = json_decode($datos['planPagos'], true);
        }

        $pdf = Pdf::loadView('pdfs.cotizacion', compact('datos'));

        // Descarga el PDF con un nombre de archivo dinámico
        $nombreArchivo = 'cotizacion-' . date('Y-m-d') . '.pdf';
        return $pdf->download($nombreArchivo);
    }
}