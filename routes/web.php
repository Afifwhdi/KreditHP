<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Route untuk redirect ke admin/login
Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});


// Test
// Route::get('/cron/scheduled-reminder', function () {
//     $now = now()->setTimezone('Asia/Jakarta');
//     $targetDate = '2025-09-18';
//     $targetTime = '11:06';

//     $targetDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', "$targetDate $targetTime", 'Asia/Jakarta');

//     if ($now->format('Y-m-d H:i') !== "$targetDate $targetTime") {
//         $diffInSeconds = $now->diffInSeconds($targetDateTime, false);
//         $diffInMinutes = $now->diffInMinutes($targetDateTime, false);

//         $hours = floor(abs($diffInMinutes) / 60);
//         $minutes = abs($diffInMinutes) % 60;
//         $seconds = abs($diffInSeconds) % 60;

//         $countdownText = '';
//         if ($diffInSeconds > 0) {
//             $countdownText = sprintf('%02d:%02d:%02d tersisa', $hours, $minutes, $seconds);
//         } else {
//             $countdownText = 'Waktu sudah lewat ' . sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
//         }

//         return response()->json([
//             'status' => 'waiting',
//             'current_time' => $now->format('Y-m-d H:i:s'),
//             'target_time' => "$targetDate $targetTime:00",
//             'countdown' => $countdownText,
//             'timezone' => 'WIB'
//         ]);
//     }

//     try {
//         $results = [];

//         $installmentsH1 = \App\Models\Installment::with(['credit.customer', 'credit.product'])
//             ->whereDate('due_date', $now->addDay())
//             ->where('status', 'DUE')
//             ->get();

//         $templateH1 = \App\Models\MessageTemplate::where('name', 'Reminder H-1')->first();

//         if ($templateH1 && $installmentsH1->count() > 0) {
//             foreach ($installmentsH1 as $installment) {
//                 $customer = $installment->credit->customer;

//                 $message = str_replace(
//                     ['{{name}}', '{{month}}', '{{due_date}}', '{{product}}', '{{amount}}'],
//                     [
//                         $customer->name,
//                         $installment->seq,
//                         $installment->due_date->format('d-m-Y'),
//                         $installment->credit->product->name,
//                         number_format($installment->amount_due, 0, ',', '.')
//                     ],
//                     $templateH1->content
//                 );

//                 $waResult = app(\App\Services\WhatsAppService::class)->sendMessage($customer, $message);

//                 \App\Models\Notification::create([
//                     'customer_id'    => $customer->id,
//                     'installment_id' => $installment->id,
//                     'template_id'    => $templateH1->id,
//                     'status'         => $waResult ? 'SENT' : 'FAILED',
//                     'sent_at'        => $now,
//                 ]);

//                 $results[] = [
//                     'type' => 'H-1',
//                     'customer' => $customer->name,
//                     'phone' => $customer->phone,
//                     'wa_status' => $waResult ? 'SENT' : 'FAILED'
//                 ];
//             }
//         }

//         $installmentsHplus1 = \App\Models\Installment::with(['credit.customer', 'credit.product'])
//             ->whereDate('due_date', $now->subDay())
//             ->where('status', 'DUE')
//             ->get();

//         $templateHplus1 = \App\Models\MessageTemplate::where('name', 'Telat H+1')->first();

//         if ($templateHplus1 && $installmentsHplus1->count() > 0) {
//             foreach ($installmentsHplus1 as $installment) {
//                 $customer = $installment->credit->customer;

//                 $message = str_replace(
//                     ['{{name}}', '{{month}}', '{{due_date}}', '{{product}}', '{{amount}}'],
//                     [
//                         $customer->name,
//                         $installment->seq,
//                         $installment->due_date->format('d-m-Y'),
//                         $installment->credit->product->name,
//                         number_format($installment->amount_due, 0, ',', '.')
//                     ],
//                     $templateHplus1->content
//                 );

//                 $waResult = app(\App\Services\WhatsAppService::class)->sendMessage($customer, $message);

//                 \App\Models\Notification::create([
//                     'customer_id'    => $customer->id,
//                     'installment_id' => $installment->id,
//                     'template_id'    => $templateHplus1->id,
//                     'status'         => $waResult ? 'SENT' : 'FAILED',
//                     'sent_at'        => $now,
//                 ]);

//                 $results[] = [
//                     'type' => 'H+1',
//                     'customer' => $customer->name,
//                     'phone' => $customer->phone,
//                     'wa_status' => $waResult ? 'SENT' : 'FAILED'
//                 ];
//             }
//         }

//         return response()->json([
//             'status' => 'executed',
//             'message' => 'Reminder berhasil dikirim otomatis!',
//             'execution_time' => $now->format('Y-m-d H:i:s'),
//             'total_sent' => count($results),
//             'details' => $results,
//             'timezone' => 'WIB'
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'error' => $e->getMessage(),
//             'execution_time' => $now->format('Y-m-d H:i:s')
//         ], 500);
//     }
// });
