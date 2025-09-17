<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class DummyCustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::updateOrCreate(
            ['phone' => '6285783100289'], // format internasional
            [
                'name' => 'Ferry Rus',
                'address' => 'Jl. Dummy No. 123',
            ]
        );
    }
}
