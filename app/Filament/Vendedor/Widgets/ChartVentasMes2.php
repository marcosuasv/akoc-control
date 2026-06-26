<?php

namespace App\Filament\Vendedor\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Venta;
use Carbon\Carbon;
use Filament\Forms;
    // Usa Carbon para manejar fechas y datos en español
    
    use Carbon\CarbonImmutable; 

class ChartVentasMes2 extends ChartWidget
{
    protected ?string $heading = 'Ventas por Mes (Últimos 12 meses)';

    protected ?string $pollingInterval = '10s'; // Actualiza cada 10 segundos
    
    protected bool $isCollapsible = true; // Permite que el widget sea colapsable
    
    protected bool $isCard = false; // Elimina el contenedor de tarjeta predeterminado

    public ?int $year = null;

    // FILTRO DE AÑO
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('year')
                ->label('Año')
                ->options($this->getYears())
                ->default(now()->year)
                ->reactive()
                ->afterStateUpdated(fn () => $this->updateChartData())
        ];
    }

    // Obtiene todos los años donde existan ventas
    private function getYears(): array
    {
        return Venta::selectRaw('YEAR(fecha) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year', 'year')
            ->toArray();
    }

    // DATOS DEL GRÁFICO

    protected function getData(): array
    {
        Carbon::setLocale('es'); // Establece el idioma a español

        $year = $this->year ?? now()->year;

        // Lista de últimos 12 meses hacia atrás desde hoy
        $months = collect();
        $start = now()->subMonths(11)->startOfMonth();

        for ($i = 0; $i < 12; $i++) {
            $months->push($start->copy()->addMonths($i));
        }

        // Contar ventas por cada mes
        $ventasPorMes = $months->map(function ($month) {
            return Venta::whereYear('fecha', $month->year)
                ->whereMonth('fecha', $month->month)
                ->count();
        });

        return [
            'labels' => $months->map(fn ($m) => $m->translatedFormat('F Y')),
            'datasets' => [
                [
                    'label' => 'Ventas',
                    'data' => $ventasPorMes,
                    'backgroundColor' => '#66d566',
                    'borderColor' => '#607960',
                    
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
