<?php

namespace App\Filament\Vendedor\Widgets;

use App\Models\Departamento;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;


class ChartVenta2 extends ChartWidget
{
    
    protected ?string $heading = 'Ventas por Estado';
    
    protected ?string $pollingInterval = '10s'; // Actualiza cada 10 segundos
    
    protected bool $isCollapsible = true; // Permite que el widget sea colapsable

    protected bool $isCard = false; // Elimina el contenedor de tarjeta predeterminado

    protected function getData(): array
    {
        $total = Departamento::count();
        $vendidos = Departamento::where('estatus', 'vendido')->count();
        $disponibles = Departamento::where('estatus', 'disponible')->count();

        return [
            'autoPadding' => true,
            'labels' => [
                        "Vendidos: {$vendidos}",
                        "Disponibles: {$disponibles}",
                        "Total: {$total}",
            ],
            'datasets' => [
                [
                    'label' => 'Departamentos',
                    'data' => [$vendidos, $disponibles],
                    'backgroundColor' => [
                        '#004d8b', // vendidos
                        '#66d566', // disponibles
                        '#607960', // total
                    ],
                ],
            ],
        ];
    }


protected function getOptions(): array
{
    return [
        'plugins' => [
            'datalabels' => [
                'color' => '#fff',
                'font' => [
                    'size' => 16,
                    'weight' => 'bold',
                ],
                'formatter' => function ($value, $ctx) {
                    return $value;   // Puedes regresar porcentaje si quieres
                },
            ],
            'legend' => [
                'position' => 'top',
            ],
        ],
    ];
}

    protected function getPlugins(): array
{
    return [
        'chartjs-plugin-datalabels',
    ];
}

    protected function getType(): string
    {
        return 'pie';
    }
}

