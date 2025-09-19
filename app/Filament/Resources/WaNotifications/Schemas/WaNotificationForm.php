<?php

namespace App\Filament\Resources\WaNotifications\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class WaNotificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('status')
                    ->label('Status')
                    ->disabled(),

                Forms\Components\DateTimePicker::make('sent_at')
                    ->label('Waktu Kirim')
                    ->disabled(),

                Forms\Components\Textarea::make('message')
                    ->label('Pesan')
                    ->disabled()
                    ->rows(3),
            ]);
    }
}
