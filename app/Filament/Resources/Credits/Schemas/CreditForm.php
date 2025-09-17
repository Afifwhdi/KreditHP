<?php

namespace App\Filament\Resources\Credits\Schemas;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;

class CreditForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('code')
                ->label('Kode Kredit')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\Select::make('customer_id')
                ->relationship('customer', 'name')
                ->label('Pelanggan')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('product_id')
                ->relationship('product', 'name')
                ->label('Produk HP')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($product = \App\Models\Product::find($state)) {
                        $set('principal_amount', $product->price);
                    }
                }),

            Forms\Components\TextInput::make('principal_amount')
                ->label('Harga HP')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('down_payment')
                ->label('DP (Uang Muka)')
                ->numeric()
                ->default(0)
                ->required(),

            Forms\Components\TextInput::make('tenor_months')
                ->label('Tenor (Bulan)')
                ->numeric()
                ->minValue(1)
                ->required(),

            Forms\Components\TextInput::make('installment_amount')
                ->label('Angsuran per Bulan')
                ->numeric()
                ->minValue(0.01)
                ->required(),

            DatePicker::make('start_date')
                ->label('Tanggal Mulai')
                ->format('Y-m-d')
                ->displayFormat('d M Y')
                ->native(false)
                ->required(),

            Forms\Components\TextInput::make('target_amount')
                ->label('Total yang Harus Dibayar')
                ->numeric()
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'ACTIVE' => 'Aktif',
                    'LUNAS'  => 'Lunas',
                    'TELAT'  => 'Telat',
                ])
                ->default('ACTIVE')
                ->required(),
        ];
    }
}
