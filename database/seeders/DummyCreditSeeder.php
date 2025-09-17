<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Credit;
use App\Models\Installment;

class DummyCreditSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::firstOrCreate(
            ['phone' => '6285783100289'],
            ['name' => 'Ferry Rus', 'address' => 'Jl. Dummy No. 123']
        );

        $credit = Credit::updateOrCreate(
            ['customer_id' => $customer->id, 'product_id' => 1], // pastikan product_id=1 ada
            ['months' => 6, 'total_amount' => 3000000]  // ✅ ganti duration_months -> months
        );

        // Buat cicilan jatuh tempo besok (untuk test H-1)
        Installment::updateOrCreate(
            ['credit_id' => $credit->id, 'seq' => 1],
            ['due_date' => now()->addDay(), 'amount_due' => 500000, 'status' => 'DUE']
        );

        // Buat cicilan jatuh tempo kemarin (untuk test H+1)
        Installment::updateOrCreate(
            ['credit_id' => $credit->id, 'seq' => 2],
            ['due_date' => now()->subDay(), 'amount_due' => 500000, 'status' => 'DUE']
        );
    }
}
