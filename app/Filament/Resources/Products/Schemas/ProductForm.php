<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms;

class ProductForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('Nama Produk')->required(),
            Forms\Components\TextInput::make('brand')->label('Merek'),
            Forms\Components\TextInput::make('price')->label('Harga')->numeric()->required(),
            Forms\Components\TextInput::make('stock_qty')->label('Stok')->numeric()->default(0),
        ];
    }
}
