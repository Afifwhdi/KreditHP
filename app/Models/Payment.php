<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MessageTemplate;
use App\Models\WaNotification;
use App\Services\WhatsAppService;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'credit_id',
        'customer_id',
        'method',
        'amount',
        'paid_at',
        'evidence_path',
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function allocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    protected static function booted()
    {
        static::created(function ($payment) {
            $customer = $payment->customer;
            $credit   = $payment->credit;

            $installment = $credit->installments()
                ->whereIn('status', ['DUE', 'PARTIAL'])
                ->orderBy('seq', 'asc')
                ->first();

            if ($installment) {
                $installment->amount_paid += $payment->amount;

                if ($installment->amount_paid >= $installment->amount_due) {
                    $installment->status = 'PAID';
                } elseif ($installment->amount_paid > 0) {
                    $installment->status = 'PARTIAL';
                }

                $installment->save();
            }

            $template = MessageTemplate::where('name', 'Terima Kasih')->first();

            if ($template && $customer) {
                $message = str_replace(
                    ['{{name}}', '{{month}}', '{{due_date}}', '{{product}}', '{{amount}}'],
                    [
                        $customer->name,
                        $installment?->seq ?? '-',
                        optional($installment?->due_date)->format('d-m-Y') ?? now()->format('d-m-Y'),
                        $credit->product->name ?? '-',
                        number_format($payment->amount, 0, ',', '.'),
                    ],
                    $template->content
                );

                $status = app(WhatsAppService::class)->sendMessage($customer, $message)
                    ? 'SENT'
                    : 'FAILED';

                WaNotification::create([
                    'customer_id'    => $customer->id,
                    'installment_id' => $installment?->id,
                    'template_id'    => $template->id,
                    'status'         => $status,
                    'sent_at'        => now(),
                ]);
            }
        });
    }
}
