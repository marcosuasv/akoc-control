<?php

namespace App\Observers;

use App\Models\Abono;
use App\Models\PlanPago; 

class AbonoObserver
{

    public function saved(Abono $abono): void
    {
        $this->actualizarStatusPlanPago($abono->plan_pago_id);
    }


    public function deleted(Abono $abono): void
    {
        $this->actualizarStatusPlanPago($abono->plan_pago_id);
    }

    public function restored(Abono $abono): void
    {
        $this->actualizarStatusPlanPago($abono->plan_pago_id);
    }

    protected function actualizarStatusPlanPago(?int $planId): void
    {
        if (!$planId) {
            return;
        }

        $plan = PlanPago::find($planId);
        if (!$plan) {
            return;
        }


        $plan->refresh();

        if ($plan->saldo <= 0) {

            $plan->update([
                'status' => 'pagado',
                'fecha_pago' => now() // Marcamos la fecha de liquidación
            ]);
        } 
        else if ($plan->montoAbonado > 0 && $plan->saldo > 0) {

            $plan->update([
                'status' => 'parcial',
                'fecha_pago' => null  
            ]);
        } 
        else {

            $plan->update([
                'status' => 'pendiente',
                'fecha_pago' => null
            ]);
        }
    }
}