<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms;

class CustomerForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required(),

            Forms\Components\TextInput::make('phone')
                ->label('Nomor HP')
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\Textarea::make('address')
                ->label('Alamat'),

            Forms\Components\TextInput::make('nik')
                ->label('NIK KTP')
                ->numeric()
                ->nullable()
                ->unique(ignoreRecord: true)
                ->maxLength(16),

            Forms\Components\FileUpload::make('ktp_path')
                ->label('Foto KTP')
                ->directory('customers/ktp')
                ->disk('public')
                ->image()
                ->nullable(),
        ];
    }
}
