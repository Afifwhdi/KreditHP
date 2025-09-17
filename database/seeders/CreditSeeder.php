<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Credit;
use App\Models\Installment;
use App\Models\Customer;
use Carbon\Carbon;

class CreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 1 customer dummy
        $customer = Customer::create([
            'name' => 'Afif Wahidi',
            'phone' => '081234567890',
            'address' => 'Lampung',
        ]);

        // Buat 1 kredit untuk customer ini
        $credit = Credit::create([
            'customer_id' => $customer->id,
            'product' => 'Samsung Galaxy A55',
            'total_price' => 5000000,
            'tenor' => 10, // cicilan 10 bulan
        ]);

        // Generate cicilan (installments)
        $perBulan = $credit->total_price / $credit->tenor;
        $startDate = Carbon::parse('2025-09-15');

        for ($i = 1; $i <= $credit->tenor; $i++) {
            Installment::create([
                'credit_id'   => $credit->id,
                'seq'         => $i,
                'due_date'    => $startDate->copy()->addMonths($i - 1),
                'amount_due'  => $perBulan,
                'amount_paid' => 0,
                'status'      => 'DUE',
            ]);
        }

        $this->command->info("✅ Kredit + cicilan dummy berhasil dibuat!");
    }
}
