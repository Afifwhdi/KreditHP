<?php

namespace App\Filament\Resources\Notifications\Schemas;

use Filament\Forms;

class NotificationsForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('status')->disabled(),
            Forms\Components\DateTimePicker::make('sent_at')->disabled(),
        ];
    }
}
