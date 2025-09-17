<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Installment;
use App\Models\MessageTemplate;
use App\Models\Notification;
use App\Services\WhatsAppService;

class SendReminders extends Command
{
    protected $signature = 'send:reminders';
    protected $description = 'Kirim reminder cicilan (H-1 & H+1) ke pelanggan';

    public function handle()
    {
        $this->info("🔔 Menjalankan reminder cicilan...");

        // === Reminder H-1 ===
        $installmentsH1 = Installment::whereDate('due_date', now()->addDay())
            ->where('status', 'DUE')
            ->get();

        $templateH1 = MessageTemplate::where('name', 'Reminder H-1')->first();

        if ($templateH1) {
            foreach ($installmentsH1 as $installment) {
                $customer = $installment->credit->customer;

                $message = str_replace(
                    ['{{name}}', '{{month}}', '{{due_date}}', '{{product}}', '{{amount}}'],
                    [
                        $customer->name,
                        $installment->seq,
                        $installment->due_date->format('d-m-Y'),
                        $installment->credit->product->name,
                        number_format($installment->amount_due, 0, ',', '.')
                    ],
                    $templateH1->content
                );

                $status = app(WhatsAppService::class)->sendMessage($customer, $message)
                    ? 'SENT'
                    : 'FAILED';

                Notification::create([
                    'customer_id'    => $customer->id,
                    'installment_id' => $installment->id,
                    'template_id'    => $templateH1->id,
                    'status'         => $status,
                    'sent_at'        => now(),
                ]);

                $this->info("✅ Reminder H-1 ke {$customer->phone} ({$status})");
            }
        }

        // === Reminder H+1 ===
        $installmentsHplus1 = Installment::whereDate('due_date', now()->subDay())
            ->where('status', 'DUE')
            ->get();

        $templateHplus1 = MessageTemplate::where('name', 'Telat H+1')->first();

        if ($templateHplus1) {
            foreach ($installmentsHplus1 as $installment) {
                $customer = $installment->credit->customer;

                $message = str_replace(
                    ['{{name}}', '{{month}}', '{{due_date}}', '{{product}}', '{{amount}}'],
                    [
                        $customer->name,
                        $installment->seq,
                        $installment->due_date->format('d-m-Y'),
                        $installment->credit->product->name,
                        number_format($installment->amount_due, 0, ',', '.')
                    ],
                    $templateHplus1->content
                );

                $status = app(WhatsAppService::class)->sendMessage($customer, $message)
                    ? 'SENT'
                    : 'FAILED';

                Notification::create([
                    'customer_id'    => $customer->id,
                    'installment_id' => $installment->id,
                    'template_id'    => $templateHplus1->id,
                    'status'         => $status,
                    'sent_at'        => now(),
                ]);

                $this->info("✅ Reminder H+1 ke {$customer->phone} ({$status})");
            }
        }

        return Command::SUCCESS;
    }
}
