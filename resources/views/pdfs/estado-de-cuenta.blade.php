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
            width: 40%;
            text-align: left;
        }
        .logo-cell img {
            max-width: 100%;
            height: auto;
            max-height: 160px;
        }
        .company-info {
            width: 50%;
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
        .status-por-pagar { background-color: #fff3cd; color: #856404; }
        .status-pendiente { background-color: #f8d7da; color: #721c24; }
        
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
                        $logoPath1 = public_path('images/logoepiso.png');
                    @endphp

                    @if(file_exists($logoPath1))
                        <img src="{{ $logoPath1 }}" alt="Logo 1">
                    @else
                        <p style="color: #ccc; font-size: 9px;">[Logo 1 no encontrado]</p>
                    @endif
                </td>
                
                <td class="logo-cell">
                
                </td>

                <td class="company-info text-right">
                    <p class="text-bold" style="font-size: 14px;">
                        {{ mb_convert_encoding('HIDALMA', 'HTML-ENTITIES', 'UTF-8') }}
                    </p>
                    <p>
                        {{ mb_convert_encoding('RFC: IAK010302C36', 'HTML-ENTITIES', 'UTF-8') }}
                    </p>
                    <p>
                        {{ mb_convert_encoding('Av. Miguel Hidalgo y Costilla 2752, Vallarta Nte.', 'HTML-ENTITIES', 'UTF-8') }}
                    </p>
                    <p>
                        {{ mb_convert_encoding('C.P. 44690 Guadalajara, Jal.', 'HTML-ENTITIES', 'UTF-8') }}
                    </p>
                    <p>Tel: (33) 3956 1083</p>
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
                            {{-- Nombre Legal (Razón Social o Nombre Completo Fiscal) --}}
                            <p><strong>Cliente:</strong> {{ mb_convert_encoding($clientePrincipal->razon_social ?? "{$clientePrincipal->nombre} {$clientePrincipal->apellidos}", 'HTML-ENTITIES', 'UTF-8') }}</p>
                            
                            {{-- RFC del Cliente --}}
                            <p><strong>RFC:</strong> {{ mb_convert_encoding($clientePrincipal->rfc ?? 'N/A', 'HTML-ENTITIES', 'UTF-8') }}</p>
                            
                            {{-- Contacto (Persona que atiende) --}}
                            <p><strong>Atención:</strong> {{ mb_convert_encoding("{$clientePrincipal->nombre} {$clientePrincipal->apellidos}", 'HTML-ENTITIES', 'UTF-8') }}</p>

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

                    <td class="align-top" style="padding-right: 15px;">
                        @php
                            $todosAbonos = $venta->planPagos->flatMap->abonos;
                            $totalPagado = $todosAbonos->sum('monto');
                            $saldoPendiente = $venta->monto_total_venta - $totalPagado;
                            
                            $pagosPorTipo = $todosAbonos->groupBy(function($abono) {
                                return $abono->pago->metodo_pago ?? 'Otro';
                            })->map(function ($group) {
                                return $group->sum('monto');
                            });
                        @endphp

                        <table class="w-100" style="font-size: 10px;">
                            <tr>
                                <td style="padding: 2px 0;"><strong style="min-width: 0;">Monto Total Venta:</strong></td>
                                <td class="text-right" style="padding: 2px 0; font-weight: bold;">${{ number_format($venta->monto_total_venta, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 0;"><strong style="min-width: 0;">Total Pagado:</strong></td>
                                <td class="text-right" style="padding: 2px 0; color: #155724;">${{ number_format($totalPagado, 2) }}</td>
                            </tr>

                            @if($pagosPorTipo->count() > 0 && $totalPagado > 0)
                                <tr>
                                    <td colspan="2" style="padding: 0; padding-left: 15px;">
                                        <table class="w-100" style="font-size: 9px; border-left: 2px solid #eee;">
                                            @foreach($pagosPorTipo as $tipo => $montoTipo)
                                                <tr>
                                                    <td class="text-right" style="padding: 1px 0 1px 5px;"><strong>{{ mb_convert_encoding(ucfirst(str_replace('_', ' ', $tipo)), 'HTML-ENTITIES', 'UTF-8') }}:</strong></td>
                                                    <td class="text-right" style="padding: 1px 0;">${{ number_format($montoTipo, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td style="padding: 2px 0;"><strong style="min-width: 0;">Saldo Pendiente:</strong></td>
                                <td class="text-right" style="padding: 2px 0; font-weight: bold; color: #c00;">${{ number_format($saldoPendiente, 2) }}</td>
                            </tr>
                            
                            <tr>
                                <td colspan="2" style="padding-top: 5px; border-top: 1px solid #eee;"></td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 0;"><strong style="min-width: 0;">No. Pagos:</strong></td>
                                <td class="text-right" style="padding: 2px 0;">{{ $venta->n_pagos }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 0;"><strong style="min-width: 0;">Frecuencia:</strong></td>
                                <td class="text-right" style="padding: 2px 0;">{{ mb_convert_encoding($venta->frecuencia_pagos, 'HTML-ENTITIES', 'UTF-8') }}</td>
                            </tr>
                        </table>
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
                @php
                    $totalMontoCuotas = $venta->planPagos->sum('monto');
                    $totalSaldoCuotas = $venta->planPagos->reject(fn($cuota) => $cuota->status === 'pagado')->sum('saldo');
                @endphp
                @forelse($venta->planPagos->sortBy('numero_pago') as $cuota)
                    <tr>
                        <td class="text-center text-bold">{{ $cuota->numero_pago }}</td>
                        <td class="text-center">{{ $cuota->fecha_vencimiento->format('d/m/Y') }}</td>
                        <td class="text-right">${{ number_format($cuota->monto, 2) }}</td>
                        <td class="text-center">
                            @php
                                $statusTexto = '';
                                $statusClass = '';

                                if ($cuota->status === 'pagado') {
                                    $statusTexto = 'Pagado';
                                    $statusClass = 'pagado';
                                } elseif ($cuota->status === 'parcial') {
                                    $statusTexto = 'Pendiente';
                                    $statusClass = 'pendiente';
                                } elseif ($cuota->status === 'pendiente') {
                                    if ($cuota->fecha_vencimiento->isFuture()) {
                                        $statusTexto = 'Por Pagar';
                                        $statusClass = 'por-pagar';
                                    } else {
                                        $statusTexto = 'Pendiente';
                                        $statusClass = 'pendiente';
                                    }
                                } else {
                                    $statusTexto = ucfirst($cuota->status);
                                    $statusClass = $cuota->status;
                                }
                            @endphp
                            <span class="status status-{{ $statusClass }}">
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
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay un plan de pagos definido.</td>
                    </tr>
                @endforelse

                @if($venta->planPagos->count() > 0)
                <tr>
                    <td colspan="2" class="text-left" style="border-top: 2px solid #333; font-weight: bold; background-color: #f0f0f0; padding: 8px;">Totales:</td>
                    <td class="text-right" style="border-top: 2px solid #333; font-weight: bold; background-color: #f0f0f0; padding: 6px;">${{ number_format($totalMontoCuotas, 2) }}</td>
                    <td style="border-top: 2px solid #333; font-weight: bold; background-color: #f0f0f0; padding: 8px;"></td>
                    <td class="text-right" style="border-top: 2px solid #333; font-weight: bold; background-color: #f0f0f0; padding: 6px;">${{ number_format($totalSaldoCuotas, 2) }}</td>
                </tr>
                @endif
            </tbody>
            
        </table>

    </div>
</body>
</html>