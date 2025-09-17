<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class CustomersTable
{
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor HP')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK KTP')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(20),

                Tables\Columns\ImageColumn::make('ktp_path')
                    ->label('Foto KTP')
                    ->square()
                    ->size(60)
                    ->disk('public')
                    ->visibility('public')
                    ->defaultImageUrl(url('/images/no-image.png')),

                Tables\Columns\TextColumn::make('credits_count')
                    ->counts('credits')
                    ->label('Jumlah Kredit'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
