<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; line-height: 1.6; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #000; }
        .header p { margin: 0; }
        .section { margin-bottom: 20px; padding: 15px; border: 1px solid #eee; border-radius: 5px; }
        .section h2 { margin-top: 0; font-size: 16px; border-bottom: 2px solid #eee; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- Puedes poner aquí tu logo -->
            <h1>Propuesta de Inversión</h1>
            <p>Fecha de cotización: {{ date('d/m/Y') }}</p>
        </div>

        <div class="section">
            <h2>Detalles del Cliente</h2>
            <p><strong>Cliente:</strong> {{ $datos['clienteNombre'] ?? 'No especificado' }}</p>
        </div>

        <div class="section">
            <h2>Detalles de la Propiedad</h2>
            <p><strong>Desarrollo:</strong> {{ $datos['desarrolloNombre'] ?? 'N/A' }}</p>
            <p><strong>Departamento:</strong> N° {{ $datos['deptoNumero'] ?? 'N/A' }} - Modelo {{ $datos['deptoModelo'] ?? 'N/A' }}</p>
            <p><strong>Precio de Venta:</strong> ${{ number_format($datos['montoTotal'] ?? 0, 2) }}</p>
        </div>

        <div class="section">
            <h2>Resumen Financiero</h2>
            <p><strong>Monto Total:</strong> ${{ number_format($datos['montoTotal'] ?? 0, 2) }}</p>
            <p><strong>Enganche:</strong> ${{ number_format($datos['enganche'] ?? 0, 2) }}</p>
            <p><strong>Monto a Financiar:</strong> ${{ number_format(($datos['montoTotal'] ?? 0) - ($datos['enganche'] ?? 0), 2) }}</p>
            <p><strong>Tasa de Interés Anual:</strong> {{ $datos['intereses'] ?? 0 }}%</p>
            <p><strong>Plazo:</strong> {{ $datos['nPagos'] ?? 0 }} pagos con frecuencia {{ $datos['frecuencia'] ?? 'N/A' }}</p>
        </div>

        @if (!empty($datos['planPagos']))
            <div class="section">
                <h2>Plan de Pagos Detallado</h2>
                <table>
                    <thead>
                        <tr>
                            <th># Pago</th>
                            <th>Fecha de Vencimiento</th>
                            <th class="text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datos['planPagos'] as $pago)
                            <tr>
                                <td>{{ $pago['numero_pago'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($pago['fecha_vencimiento'])->format('d/m/Y') }}</td>
                                <td class="text-right">${{ number_format($pago['monto'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="footer">
            <p>Esta es una cotización preliminar y está sujeta a cambios sin previo aviso. Los términos y condiciones finales se establecerán en el contrato de compra-venta.</p>
        </div>
    </div>
</body>
</html>