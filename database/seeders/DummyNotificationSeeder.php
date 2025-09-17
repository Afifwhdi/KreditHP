<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\Customer;
use App\Models\Installment;
use App\Models\MessageTemplate;

class DummyNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::first();
        $installment = Installment::first();
        $template = MessageTemplate::first();

        if ($customer && $installment && $template) {
            Notification::create([
                'customer_id' => $customer->id,
                'installment_id' => $installment->id,
                'template_id' => $template->id,
                'status' => 'SENT',
                'sent_at' => now()->subMinutes(30),
            ]);

            Notification::create([
                'customer_id' => $customer->id,
                'installment_id' => $installment->id,
                'template_id' => $template->id,
                'status' => 'FAILED',
                'sent_at' => now()->subMinutes(10),
            ]);
        }
    }
}
