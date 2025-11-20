<x-filament-panels::page> 
    <div style="margin-top: -1.5rem;"> 
        <div style="background-color: #ffffff; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
            
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb;">
            </h3>
        </div>
        
        <div style="padding: 1.5rem;">

            <!-- ================================= -->
            <!-- ===== INICIO: FILTRO NUEVO ====== -->
            <!-- ================================= -->
            <div style="margin-bottom: 1.5rem;">
                <label for="desarrollo_filter" style="display: block; font-size: 0.875rem; font-weight: 500; color: #111827; margin-bottom: 0.5rem;">
                    Filtrar por Proyecto
                </label>
                <select id="desarrollo_filter"
                    wire:model.live="selectedDesarrolloId"
                    style="width: 100%; max-width: 400px; border-radius: 0.375rem; border: 1px solid #d1d5db; padding: 0.5rem 1rem; background-color: #ffffff; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);"
                >
                    <option value="">Todos los Proyectos</option>
                    @foreach($this->desarrollos as $id => $nombre)
                        <option value="{{ $id }}">{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>
            <!-- ================================= -->
            <!-- ======== FIN: FILTRO NUEVO ======== -->
            <!-- ================================= -->


            <div style="overflow-x: auto;">
                <table style="width: 100%; text-align: left; font-size: 0.875rem; color: #6b7280;">
                    
                    <thead style="font-size: 0.75rem; text-transform: uppercase; color: #374151; background-color: #f9fafb;">
                        <tr>
                            <th scope="col" style="padding: 0.75rem 1.5rem;">Mes</th>
                            <th scope="col" style="padding: 0.75rem 1.5rem;">Total Programado</th>
                            <th scope="col" style="padding: 0.75rem 1.5rem;">Total Pagado</th>
                            <th scope="col" style="padding: 0.75rem 1.5rem;">Saldo Pendiente</th>
                            <th scope="col" style="padding: 0.75rem 1.5rem;">Cuotas Vencidas</th>
                        </tr>
                    </thead>

                    <tbody>
                        
                        {{-- ========= FILA 1: TOTAL VENCIDO (Ahora usa la prop. computada) ========= --}}
                        <tr style="background-color: #fef2f2; border-bottom: 2px solid #dc2626;">
                            <th scope="row" style="padding: 1rem 1.5rem; font-weight: 700; color: #991b1b; white-space: nowrap;">
                                Saldo Vencido
                            </th>
                            <td style="padding: 1rem 1.5rem; font-weight: 600;">
                                ${{ number_format($this->reporteVencido['total_programado'], 2) }}
                            </td>
                            <td style="padding: 1rem 1.5rem; color: #15803d; font-weight: 600;">
                                ${{ number_format($this->reporteVencido['total_pagado'], 2) }}
                            </td>
                            <td style="padding: 1rem 1.5rem; color: #dc2626; font-weight: 700;">
                                ${{ number_format($this->reporteVencido['saldo_pendiente'], 2) }}
                            </td>
                            <td style="padding: 1rem 1.5rem; color: #dc2626; font-weight: 700;">
                                {{ $this->reporteVencido['cuotas_vencidas'] }}
                            </td>
                        </tr>

                        {{-- ========= FILA 2: PROYECCIÓN (PAGINADA) ========= --}}
                        @forelse ($this->proyeccionPaginada as $mes)
                            <tr style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb;">
                                
                                <th scope="row" style="padding: 1rem 1.5rem; font-weight: 500; color: #111827; white-space: nowrap;">
                                    {{ $mes['mes'] }}
                                </th>
                                <td style="padding: 1rem 1.5rem;">
                                    ${{ number_format($mes['total_programado'], 2) }}
                                </td>
                                <td style="padding: 1rem 1.5rem; color: #15803d; font-weight: 600;">
                                    ${{ number_format($mes['total_pagado'], 2) }}
                                </td>
                                <td style="padding: 1rem 1.5rem; color: #ea580c; font-weight: 600;">
                                    ${{ number_format($mes['saldo_pendiente'], 2) }}
                                </td>
                                <td style="padding: 1rem 1.5rem; color: #dc2626; font-weight: 700;">
                                    {{ $mes['cuotas_vencidas'] }}
                                </td>
                            </tr>
                        @empty
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td colspan="5" style="padding: 1rem 1.5rem; text-align: center; color: #6b7280;">
                                    @if($this->selectedDesarrolloId)
                                        No hay datos de proyección futuros para este proyecto.
                                    @else
                                        No hay datos de proyección futuros.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ========= 3. ENLACES DE PAGINACIÓN ========= --}}
            <div style="padding-top: 1rem;">
                {{ $this->proyeccionPaginada->links() }}
            </div>
        </div>
    </div>
</x-filament-panels::page>