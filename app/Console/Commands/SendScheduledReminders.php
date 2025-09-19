<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Installment;
use App\Models\MessageTemplate;
use App\Models\WaNotification;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class SendScheduledReminders extends Command
{
    protected $signature = 'send:scheduled-reminders {mode?}';
    protected $description = 'Kirim reminder cicilan H-1 atau H+1 sesuai jadwal';

    public function handle()
    {
        $now = Carbon::now('Asia/Jakarta');
        $mode = $this->argument('mode');

        $this->info("🔔 Scheduler reminder dijalankan [mode={$mode}] {$now}");

        if (!$mode || $mode === 'h-1') {
            $installmentsH1 = Installment::with(['credit.customer', 'credit.product'])
                ->whereDate('due_date', $now->copy()->addDay())
                ->where('status', 'DUE')
                ->get();

            $templateH1 = MessageTemplate::where('name', 'Reminder H-1')->first();

            if ($templateH1 && $installmentsH1->count() > 0) {
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

                    $waResult = app(WhatsAppService::class)->sendMessage($customer, $message);

                    WaNotification::create([
                        'customer_id'    => $customer->id,
                        'installment_id' => $installment->id,
                        'template_id'    => $templateH1->id,
                        'status'         => $waResult ? 'SENT' : 'FAILED',
                        'sent_at'        => $now,
                    ]);

                    $this->info("✅ [H-1] {$customer->name} ({$customer->phone}) → " . ($waResult ? 'SENT' : 'FAILED'));
                }
            }
        }

        if (!$mode || $mode === 'h+1') {
            $installmentsHplus1 = Installment::with(['credit.customer', 'credit.product'])
                ->whereDate('due_date', $now->copy()->subDay())
                ->where('status', 'DUE')
                ->get();

            $templateHplus1 = MessageTemplate::where('name', 'Telat H+1')->first();

            if ($templateHplus1 && $installmentsHplus1->count() > 0) {
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

                    $waResult = app(WhatsAppService::class)->sendMessage($customer, $message);

                    WaNotification::create([
                        'customer_id'    => $customer->id,
                        'installment_id' => $installment->id,
                        'template_id'    => $templateHplus1->id,
                        'status'         => $waResult ? 'SENT' : 'FAILED',
                        'sent_at'        => $now,
                    ]);

                    $this->info("✅ [H+1] {$customer->name} ({$customer->phone}) → " . ($waResult ? 'SENT' : 'FAILED'));
                }
            }
        }

        return Command::SUCCESS;
    }
}
