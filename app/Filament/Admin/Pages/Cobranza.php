<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use UnitEnum;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use App\Models\PlanPago;
use App\Models\Desarrollo; 
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class Cobranza extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;
    protected static string|UnitEnum|null $navigationGroup = 'Depositos y Finanzas';
    protected string $view = 'filament.vendedor.pages.cobranza';
    protected static ?int $navigationSort = 3;

    // --- 2. PROPIEDADES PARA EL FILTRO ---
    public $desarrollos = [];
    public $selectedDesarrolloId = null;

    // Ya no se usan como propiedades públicas, se harán computadas
    // public $reporteVencido = [];
    // public $reporteFuturo = [];
    // private $proyeccionCompleta = [];

    public function mount(): void
    {
        // --- 3. MOUNT AHORA SOLO CARGA LOS FILTROS ---
        $this->desarrollos = Desarrollo::orderBy('nombre')->pluck('nombre', 'id')->all();
    }

    // --- 4. PROPIEDAD COMPUTADA PARA REPORTE VENCIDO ---
    public function getReporteVencidoProperty(): array
    {
        $inicioProyeccion = Carbon::now()->startOfMonth();

        // Iniciar consulta base
        $query = PlanPago::where('fecha_vencimiento', '<', $inicioProyeccion);

        // Aplicar filtro si existe
        if ($this->selectedDesarrolloId) {
            $query->whereHas('venta.departamento', function ($q) {
                $q->where('desarrollo_id', $this->selectedDesarrolloId);
            });
        }

        // Obtener resultados
        $cuotasVencidas = $query->get();

        // Calcular y devolver
        return [
            'total_programado' => $cuotasVencidas->sum('monto'),
            'total_pagado' => $cuotasVencidas->sum('montoAbonado'),
            'saldo_pendiente' => $cuotasVencidas->sum('saldo'),
            'cuotas_vencidas' => $cuotasVencidas->filter(fn($p) => $p->saldo > 0)->count(),
        ];
    }

    // --- 5. PROPIEDAD COMPUTADA PARA PROYECCIÓN COMPLETA ---
    public function getProyeccionCompletaProperty(): array
    {
        $inicioProyeccion = Carbon::now()->startOfMonth();

        // Iniciar consulta base
        $query = PlanPago::where('fecha_vencimiento', '>=', $inicioProyeccion);

        // Aplicar filtro si existe
        if ($this->selectedDesarrolloId) {
            $query->whereHas('venta.departamento', function ($q) {
                $q->where('desarrollo_id', $this->selectedDesarrolloId);
            });
        }

        // Agrupar resultados
        $planesAgrupados = $query
            ->orderBy('fecha_vencimiento', 'asc')
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
        
        return $proyeccion;
    }

    // --- 6. PAGINACIÓN (Ahora usa la propiedad computada) ---
    public function getProyeccionPaginadaProperty(): LengthAwarePaginator
    {
        // Llama a la propiedad computada
        $items = $this->proyeccionCompleta; 
        
        $perPage = 12;
        $page = Paginator::resolveCurrentPage('page');
        $paginatedItems = array_slice($items, ($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator(
            $paginatedItems,
            count($items),
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
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