<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'principal_amount',
        'down_payment',
        'tenor_months',
        'installment_amount',
        'target_amount',
        'start_date',
        'status',
        'code',
    ];

    protected static function booted()
    {
        static::creating(function ($credit) {
            $year = Carbon::now()->format('Y');
            $countThisYear = static::whereYear('created_at', $year)->count() + 1;
            $credit->code = 'CR-' . $year . '-' . str_pad($countThisYear, 3, '0', STR_PAD_LEFT);
        });

        static::created(function ($credit) {
            \App\Models\WaNotification::create([
                'customer_id' => $credit->customer_id,
                'status' => 'SENT',
                'template_id' => null,
                'installment_id' => null,
                'sent_at' => now(),
            ]);
        });

        static::created(function ($credit) {
            $tenor    = (int) ($credit->tenor_months ?? 0);
            $amount   = (float) ($credit->installment_amount ?? 0);
            $start    = $credit->start_date ? Carbon::parse($credit->start_date) : now();

            for ($i = 1; $i <= $tenor; $i++) {
                $credit->installments()->create([
                    'seq'         => $i,
                    'due_date'    => $start->copy()->addMonths($i - 1),
                    'amount_due'  => $amount,
                    'amount_paid' => 0,
                    'status'      => 'DUE',
                ]);
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'credit_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}
