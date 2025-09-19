<?php

namespace App\Filament\Resources\WaNotifications\Pages;

use App\Filament\Resources\WaNotifications\WaNotificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWaNotification extends EditRecord
{
    protected static string $resource = WaNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),
        ];
    }
}
