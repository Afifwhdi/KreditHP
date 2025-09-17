<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Form;
use Filament\Forms;

class UserForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->unique(ignoreRecord: true)
                ->required(),

            Forms\Components\FileUpload::make('avatar')
                ->label('Foto Profil')
                ->image()
                ->directory('avatars')
                ->visibility('public')
                ->maxSize(2048),

            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->revealable()
                ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                ->required(fn(string $context): bool => $context === 'create')
                ->dehydrated(fn($state) => filled($state)),
        ];
    }
}
