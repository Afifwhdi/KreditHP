<?php

namespace App\Filament\Resources\Credits\Tables;

use Filament\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class CreditsTable
{
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Kredit')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk HP')
                    ->searchable(),

                Tables\Columns\TextColumn::make('principal_amount')
                    ->label('Harga HP')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('down_payment')
                    ->label('DP')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('tenor_months')
                    ->label('Tenor (Bulan)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('installment_amount')
                    ->label('Angsuran')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('target_amount')
                    ->label('Total Bayar')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => fn($state) => $state === 'ACTIVE',
                        'success' => fn($state) => $state === 'LUNAS',
                        'danger'  => fn($state) => $state === 'TELAT',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'ACTIVE' => 'Aktif',
                        'LUNAS'  => 'Lunas',
                        'TELAT'  => 'Telat',
                        default  => $state,
                    })
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
