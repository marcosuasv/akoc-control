<?php

namespace App\Observers;

use App\Models\Abono;
use App\Models\PlanPago; // <-- Asegúrate de importar PlanPago

class AbonoObserver
{
    /**
     * Se dispara después de que un Abono es guardado (creado o actualizado).
     */
    public function saved(Abono $abono): void
    {
        $this->actualizarStatusPlanPago($abono->plan_pago_id);
    }

    /**
     * Se dispara después de que un Abono es eliminado.
     */
    public function deleted(Abono $abono): void
    {
        // Pasa el ID del plan de pago que estaba asociado
        $this->actualizarStatusPlanPago($abono->plan_pago_id);
    }

    /**
     * Se dispara después de que un Abono es restaurado (si usas Soft Deletes).
     */
    public function restored(Abono $abono): void
    {
        $this->actualizarStatusPlanPago($abono->plan_pago_id);
    }

    /**
     * Función central para actualizar el estatus del PlanPago.
     */
    protected function actualizarStatusPlanPago(?int $planId): void
    {
        if (!$planId) {
            return;
        }

        $plan = PlanPago::find($planId);
        if (!$plan) {
            return;
        }

        // Forzamos al modelo a recargar sus relaciones
        // para que los accessors 'montoAbonado' y 'saldo' se recalculen
        $plan->refresh();

        if ($plan->saldo <= 0) {
            // SALDO CERO O NEGATIVO = PAGADO
            $plan->update([
                'status' => 'pagado',
                'fecha_pago' => now() // Marcamos la fecha de liquidación
            ]);
        } 
        else if ($plan->montoAbonado > 0 && $plan->saldo > 0) {
            // TIENE ABONOS, PERO AÚN DEBE = PARCIAL
            $plan->update([
                'status' => 'parcial', // Asegúrate de tener este status
                'fecha_pago' => null  // Aún no está liquidado
            ]);
        } 
        else {
            // MONTO ABONADO ES CERO = PENDIENTE
            $plan->update([
                'status' => 'pendiente',
                'fecha_pago' => null
            ]);
        }
    }
}