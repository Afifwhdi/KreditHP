<?php

namespace App\Filament\Resources\WaNotifications\Pages;

use App\Filament\Resources\WaNotifications\WaNotificationResource;
use Filament\Resources\Pages\ListRecords;

class ListWaNotifications extends ListRecords
{
    protected static string $resource = WaNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return 'Log Notifikasi WhatsApp';
    }
}
