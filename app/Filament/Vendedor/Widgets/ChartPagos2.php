<?php

namespace App\Filament\Vendedor\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PlanPago;
use App\Models\Abono;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class ChartPagos2 extends ChartWidget
{
    protected ?string $heading = 'Cobrado vs Pendiente';

    protected ?string $pollingInterval = '10s'; // Actualiza cada 10 segundos
    
    protected bool $isCollapsible = true; // Permite que el widget sea colapsable

    protected bool $isCard = false; // Elimina el contenedor de tarjeta predeterminado

    protected function getData(): array
    {
        $total = PlanPago::sum('monto');
        $pagado = Abono::sum('monto');
        $pendiente = max($total - $pagado, 0);

        return [
            'labels' => ['Monto'],
            'datasets' => [
                [
                    'label' => "Pagado $ " ,
                    'data' => [$pagado],
                    'backgroundColor' => '#004d8b',
                ],
                [
                    'label' => "Pendiente $ " ,
                    'data' => [$pendiente],
                    'backgroundColor' => '#66d566',
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
                'datalabels' => [
                    'anchor' => 'end',
                    'align' => 'top',
                    'color' => '#000',
                    'font' => ['weight' => 'bold'],
                ],
            ],

            'scales' => [
                'y' => [
                    'beginAtZero' => false,
                    'min' => 500000,
                    'max' => 100000000,
                    'ticks' => [
                        'stepSize' => 1000000,
                    ],
                ],
            ],
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }

}
