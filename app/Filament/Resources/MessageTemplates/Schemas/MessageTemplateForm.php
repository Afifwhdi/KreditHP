<?php

namespace App\Filament\Resources\MessageTemplates\Schemas;

use Filament\Forms;

class MessageTemplateForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Nama Template')
                ->required(),

            Forms\Components\Textarea::make('content')
                ->label('Isi Pesan')
                ->rows(6)
                ->required()
                ->helperText('Gunakan {{name}}, {{month}}, {{due_date}} untuk variabel otomatis'),

            Forms\Components\Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ];
    }
}
