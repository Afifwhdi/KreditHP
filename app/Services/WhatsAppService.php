<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected string $driver;

    public function __construct()
    {
        $this->driver = config('services.whatsapp.driver', 'fonnte');
    }

    public function sendMessage($customer, string $message): bool
    {
        return match ($this->driver) {
            'fonnte' => $this->sendViaFonnte($customer->phone, $message),
            default   => false,
        };
    }

    protected function sendViaFonnte(string $number, string $message): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => config('services.whatsapp.fonnte_token'),
            ])->post("https://api.fonnte.com/send", [
                'target' => $number,
                'message' => $message,
            ]);

            return $response->json('status') === true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
