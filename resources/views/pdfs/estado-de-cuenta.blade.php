<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta - Venta #{{ $venta->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 6px;
        }
        
        .w-100 { width: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-bold { font-weight: bold; }
        .mt-20 { margin-top: 20px; }
        .mb-20 { margin-bottom: 20px; }
        .p-0 { padding: 0; }
        .align-top { vertical-align: top; }

        .header-table td {
            border: none;
            padding: 0 5px; 
            vertical-align: top;
        }
        .logo-cell {
            width: 30%;
            text-align: left;
        }
        .logo-cell img {
            max-width: 100%; 
            height: auto;
            max-height: 160px;
        }
        .company-info {
            width: 40%;
            font-size: 10px;
            color: #555;
        }

        .document-title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            color: #111;
        }

        .summary-table {
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fcfcfc;
        }
        .summary-table th {
            border-bottom: 1px solid #ddd;
            background-color: #f5f5f5;
            padding: 8px;
            font-size: 13px;
        }
        .summary-table td {
            border: none;
            border-right: 1px solid #eee;
            padding: 10px;
            font-size: 10px;
            vertical-align: top;
        }
        .summary-table td:last-child {
            border-right: none;
        }
        .summary-table p {
            margin: 0 0 5px 0;
        }
        .summary-table strong {
            display: inline-block;
            min-width: 70px;
            color: #000;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .plan-table {
            border: 1px solid #ccc;
        }
        .plan-table th {
            background-color: #333;
            color: #ffffff;
            font-size: 12px;
            padding: 8px;
        }
        .plan-table td {
            border: 1px solid #ddd;
        }
        
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
        }
        .status-pagado { background-color: #d4edda; color: #155724; }
        .status-pendiente { background-color: #fff3cd; color: #856404; }
        .status-parcial { background-color: #d1ecf1; color: #0c5460; }
        
        .abonos-detail {
            background-color: #f9f9f9;
        }
        .abonos-detail td {
            padding: 10px 15px;
        }
        .abonos-detail ul {
            margin: 0;
            padding-left: 15px;
            list-style-type: square;
        }
        .abonos-detail li {
            font-size: 10px;
            color: #444;
            margin-bottom: 3px;
        }
        .abonos-detail strong {
            color: #000;
        }

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        .footer .page-number:before {
            content: "Página " counter(page);
        }
    </style>
</head>
<body>

    <div class="footer">
        <span class="page-number"></span>
    </div>

    <div class="container">
        
        <table class="w-100 header-table mb-20">
            <tr>
                <td class="logo-cell">
                    @php
                        $logoPath1 = public_path('images/logoakoc.png');
                    @endphp

                    @if(file_exists($logoPath1))
                        <img src="{{ $logoPath1 }}" alt="Logo 1">
                    @else
                        <p style="color: #ccc; font-size: 9px;">[Logo 1 no encontrado]</p>
                    @endif
                </td>
                
                <td class="logo-cell">
                    @php
                        $logoPath2 = public_path('images/hidalma.png'); 
                    @endphp

                    @if(file_exists($logoPath2))
                        <img src="{{ $logoPath2 }}" alt="Logo 2">
                    @else
                        <p style="color: #ccc; font-size: 9px;">[Logo 2 no encontrado]</p>
                    @endif
                </td>

                <td class="company-info text-right">
                    <p class="text-bold" style="font-size: 14px;">
                        {{ mb_convert_encoding('HIDALMA', 'HTML-ENTITIES', 'UTF-8') }}
                    </p>
                    <p>
                        {{ mb_convert_encoding('RFC: XXX010101XXX', 'HTML-ENTITIES', 'UTF-8') }}
                    </p>
                    <p>
                        {{ mb_convert_encoding('Av. Miguel Hidalgo y Costilla 2752, Vallarta Nte.', 'HTML-ENTITIES', 'UTF-8') }}
                    </p>
                    <p>
                        {{ mb_convert_encoding('C.P. 44690 Guadalajara, Jal.', 'HTML-ENTITIES', 'UTF-8') }}
                    </p>
                    <p>Tel: (33 ) 3218 1961</p>
                    <p>www.hidalmaminerva.com</p>
                </td>
            </tr>
        </table>

        <h1 class="document-title">
            ESTADO DE CUENTA
        </h1>
        <p class="text-right mb-20">
            <strong>Venta Folio:</strong> {{ $venta->id }}<br>
            <strong>Fecha de Emisión:</strong> {{ now()->format('d/m/Y') }}
        </p>

        <table class="w-100 summary-table mb-20">
            <thead>
                <tr>
                    <th class="text-left">Información del Cliente</th>
                    <th class="text-left">Información de la Propiedad</th>
                    <th class="text-left">Resumen Financiero</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @php
                        $clientePrincipal = $venta->clientes->first();
                    @endphp
                    <td class="align-top">
                        @if($clientePrincipal)
                            <p><strong>Cliente:</strong> {{ mb_convert_encoding("{$clientePrincipal->nombre} {$clientePrincipal->apellidos}", 'HTML-ENTITIES', 'UTF-8') }}</p>
                            <p><strong>Correo:</strong> {{ mb_convert_encoding($clientePrincipal->correo ?? 'N/A', 'HTML-ENTITIES', 'UTF-8') }}</p>
                            <p><strong>Teléfono:</strong> {{ mb_convert_encoding($clientePrincipal->telefono ?? 'N/A', 'HTML-ENTITIES', 'UTF-8') }}</p>
                        @else
                            <p>Sin cliente principal.</p>
                        @endif
                        @if($venta->clientes->count() > 1)
                            <p><strong>Otros Clientes:</strong> {{ $venta->clientes->count() - 1 }} más.</p>
                        @endif
                    </td>

                    <td class="align-top">
                        @if($venta->departamento)
                            <p><strong>Proyecto:</strong> {{ mb_convert_encoding($venta->departamento->desarrollo->nombre ?? 'N/A', 'HTML-ENTITIES', 'UTF-8') }}</p>
                            <p><strong>Unidad:</strong> {{ mb_convert_encoding($venta->departamento->numero, 'HTML-ENTITIES', 'UTF-8') }}</p>
                            <p><strong>Modelo:</strong> {{ mb_convert_encoding($venta->departamento->modelo ?? 'N/A', 'HTML-ENTITIES', 'UTF-8') }}</p>
                            <p><strong>Precio Lista:</strong> ${{ number_format($venta->departamento->precio, 2) }}</p>
                        @else
                            <p>Sin propiedad asociada.</p>
                        @endif
                    </td>

                    <td class="align-top">
                        @php
                            $totalPagado = $venta->planPagos->flatMap->abonos->sum('monto');
                            $saldoPendiente = $venta->monto_total_venta - $totalPagado;
                        @endphp
                        <p><strong>Monto Total Venta:</strong> <span class="text-right text-bold">${{ number_format($venta->monto_total_venta, 2) }}</span></p>
                        <p><strong>Enganche:</strong> <span class="text-right">${{ number_format($venta->enganche, 2) }}</span></p>
                        <p><strong>Total Pagado:</strong> <span class="text-right" style="color: #155724;">${{ number_format($totalPagado, 2) }}</span></p>
                        <p><strong>Saldo Pendiente:</strong> <span class="text-right text-bold" style="color: #c00;">${{ number_format($saldoPendiente, 2) }}</span></p>
                        <hr style="border: 0; border-top: 1px solid #eee;">
                        <p><strong>No. Pagos:</strong> {{ $venta->n_pagos }}</p>
                        <p><strong>Frecuencia:</strong> {{ mb_convert_encoding($venta->frecuencia_pagos, 'HTML-ENTITIES', 'UTF-8') }}</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <h2 class="section-title">Plan de Pagos Detallado</h2>
        <table class="w-100 plan-table">
            <thead>
                <tr>
                    <th># Pago</th>
                    <th>Vencimiento</th>
                    <th>Monto Cuota</th>
                    <th>Estatus</th>
                    <th>Saldo Cuota</th>
                </tr>
            </thead>
            <tbody>
                @forelse($venta->planPagos->sortBy('numero_pago') as $cuota)
                    <tr>
                        <td class="text-center text-bold">{{ $cuota->numero_pago }}</td>
                        <td class="text-center">{{ $cuota->fecha_vencimiento->format('d/m/Y') }}</td>
                        <td class="text-right">${{ number_format($cuota->monto, 2) }}</td>
                        <td class="text-center">
                            @php
                                $statusTexto = match ($cuota->status) {
                                    'pagado' => 'Pagado',
                                    'parcial' => 'Parcial',
                                    'pendiente' => 'Pendiente',
                                    default => ucfirst($cuota->status),
                                };
                            @endphp
                            <span class="status status-{{ $cuota->status }}">
                                {{ mb_convert_encoding($statusTexto, 'HTML-ENTITIES', 'UTF-8') }}
                            </span>
                        </td>
                        <td class="text-right text-bold">
                            @if($cuota->status !== 'pagado')
                                ${{ number_format($cuota->saldo, 2) }}
                            @else
                                ${{ number_format(0, 2) }}
                            @endif
                        </td>
                    </tr>
                    
                    @if($cuota->abonos->count() > 0)
                        <tr class="abonos-detail">
                            <td colspan="5">
                                <strong>Abonos recibidos en esta cuota:</strong>
                                <ul>
                                    @foreach($cuota->abonos as $abono)
                                        <li>
                                            Fecha: {{ $abono->fecha_abono->format('d/m/Y') }} | 
                                            Monto: <strong>${{ number_format($abono->monto, 2) }}</strong> |
                                            Registró: {{ mb_convert_encoding($abono->user->name ?? 'N/A', 'HTML-ENTITIES', 'UTF-8') }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endif
                    
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay un plan de pagos definido.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</body>
</html>