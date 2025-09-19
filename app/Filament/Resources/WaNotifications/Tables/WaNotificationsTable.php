<?php

namespace App\Filament\Resources\WaNotifications\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class WaNotificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('installment.seq')
                    ->label('Bulan Ke')
                    ->sortable(),

                Tables\Columns\TextColumn::make('template.name')
                    ->label('Template')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'SENT',
                        'warning' => 'PENDING',
                        'danger' => 'FAILED',
                    ])
                    ->label('Status'),

                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Waktu Kirim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'SENT' => 'Terkirim',
                        'FAILED' => 'Gagal',
                        'PENDING' => 'Pending',
                    ]),

                Tables\Filters\Filter::make('sent_today')
                    ->query(fn($query) => $query->whereDate('sent_at', today()))
                    ->label('Hari Ini'),
            ])
            ->defaultSort('sent_at', 'desc');
    }
}
