<?php

namespace App\Filament\Resources\Installments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;

class InstallmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('seq')
                    ->label('Bulan ke')
                    ->numeric()
                    ->disabled(),

                DatePicker::make('due_date')
                    ->label('Tanggal Jatuh Tempo')
                    ->required(),

                TextInput::make('amount_due')
                    ->label('Nominal Cicilan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('amount_paid')
                    ->label('Nominal Dibayar')
                    ->numeric()
                    ->prefix('Rp'),

                Select::make('status')
                    ->label('Status Cicilan')
                    ->options([
                        'DUE' => 'Belum Bayar',
                        'PAID' => 'Sudah Bayar',
                        'OVERDUE' => 'Telat Bayar',
                        'PARTIAL' => 'Bayar Sebagian',
                    ])
                    ->required(),
            ]);
    }
}
