<?php

namespace App\Filament\Vendedor\Pages;

use Filament\Pages\Page;
use UnitEnum;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use App\Models\PlanPago;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
class Cobranza extends Page
{
     protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;
    protected static string|UnitEnum|null $navigationGroup = 'Depositos y Finanzas';
    protected string $view = 'filament.vendedor.pages.cobranza';
    protected static ?int $navigationSort = 3;
    public $reporteVencido = [];
    public $reporteFuturo = [];
    private $proyeccionCompleta = [];
    public function mount(): void
    {
        $hoy = Carbon::now()->startOfDay();
        $inicioProyeccion = Carbon::now()->startOfMonth();

        // --- 1. REPORTE VENCIDO (Todo lo anterior a este mes) ---
        $cuotasVencidas = PlanPago::where('fecha_vencimiento', '<', $inicioProyeccion)->get();
        $this->reporteVencido = [
            'total_programado' => $cuotasVencidas->sum('monto'),
            'total_pagado' => $cuotasVencidas->sum('montoAbonado'),
            'saldo_pendiente' => $cuotasVencidas->sum('saldo'),
            'cuotas_vencidas' => $cuotasVencidas->filter(fn($p) => $p->saldo > 0)->count(),
        ];

        // --- 2. PROYECCIÓN (Todos los meses de aquí en adelante) ---
        $planesAgrupados = PlanPago::where('fecha_vencimiento', '>=', $inicioProyeccion)
            ->orderBy('fecha_vencimiento', 'asc') // <-- Importante ordenar
            ->get()
            ->groupBy(fn($plan) => $plan->fecha_vencimiento->format('Y-m'));
        
        $proyeccion = [];
        foreach ($planesAgrupados as $key => $cuotasDelMes) {
            $proyeccion[] = [
                'mes' => Carbon::createFromFormat('Y-m', $key)->translatedFormat('F Y'),
                'total_programado' => $cuotasDelMes->sum('monto'),
                'total_pagado' => $cuotasDelMes->sum('montoAbonado'),
                'saldo_pendiente' => $cuotasDelMes->sum('saldo'),
                'cuotas_vencidas' => $cuotasDelMes->filter(fn($p) => $p->fecha_vencimiento->isPast() && $p->saldo > 0)->count(),
            ];
        }
        $this->proyeccionCompleta = $proyeccion;

        // --- 3. REPORTE FUTURO (Ya no se calcula aquí, se incluye en la proyección) ---
        // Lo dejamos vacío ya que la proyección ahora cubre todo el futuro
        $this->reporteFuturo = []; 
    }

    /**
     * Esta es una propiedad "Computada" que crea la paginación.
     * ¡Filament y Livewire la llamarán automáticamente!
     */
    public function getProyeccionPaginadaProperty(): LengthAwarePaginator
    {
        $items = $this->proyeccionCompleta;
        $perPage = 12; // 12 meses por página
        $page = Paginator::resolveCurrentPage('page'); // Nombre de la página

        $paginatedItems = array_slice($items, ($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator(
            $paginatedItems,
            count($items), // Total de meses
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page', // Asegura que use 'page'
            ]
        );
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\ListadoCobranzaWidget::class,
        ];
    }
}
