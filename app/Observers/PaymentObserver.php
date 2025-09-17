<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Installment;
use App\Models\Credit;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        $installment = Installment::where('credit_id', $payment->credit_id)
            ->whereIn('status', ['DUE', 'PARTIAL'])
            ->orderBy('seq')
            ->first();

        if (!$installment) {
            return;
        }

        $installment->amount_paid += $payment->amount;
        if ($installment->amount_paid >= $installment->amount_due) {
            $installment->status = 'PAID';
        } elseif ($installment->amount_paid > 0) {
            $installment->status = 'PARTIAL';
        }
        $installment->save();

        $remaining = Installment::where('credit_id', $payment->credit_id)
            ->where('status', '!=', 'PAID')
            ->count();

        if ($remaining === 0) {
            Credit::where('id', $payment->credit_id)->update([
                'status' => 'LUNAS',
            ]);
        }
    }
}
