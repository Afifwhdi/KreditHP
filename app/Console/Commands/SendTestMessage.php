<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\MessageTemplate;
use App\Models\Notification;
use App\Services\WhatsAppService;

class SendTestMessage extends Command
{
    protected $signature = 'send:test {phone?}';
    protected $description = 'Kirim pesan WA test pakai template pertama (single customer atau semua)';

    public function handle()
    {
        $phone = $this->argument('phone');
        $template = MessageTemplate::first();

        if (!$template) {
            $this->error("❌ Template pesan tidak ditemukan");
            return Command::FAILURE;
        }

        // Kalau ada parameter phone, ambil 1 customer, kalau tidak, ambil semua
        $customers = $phone
            ? Customer::where('phone', $phone)->get()
            : Customer::all();

        if ($customers->isEmpty()) {
            $this->error(
                $phone
                    ? "❌ Customer dengan nomor {$phone} tidak ditemukan"
                    : "❌ Tidak ada customer di database"
            );
            return Command::FAILURE;
        }

        foreach ($customers as $customer) {
            // Generate pesan
            $message = str_replace(
                ['{{name}}', '{{month}}', '{{due_date}}'],
                [$customer->name, 1, now()->format('d-m-Y')],
                $template->content
            );

            $status = app(WhatsAppService::class)->sendMessage($customer, $message)
                ? 'SENT'
                : 'FAILED';

            Notification::create([
                'customer_id'    => $customer->id,
                'installment_id' => null,
                'template_id'    => $template->id,
                'status'         => $status,
                'sent_at'        => now(),
            ]);

            $this->info("✅ Pesan test ({$status}) dikirim ke {$customer->phone}");
        }

        return Command::SUCCESS;
    }
}
