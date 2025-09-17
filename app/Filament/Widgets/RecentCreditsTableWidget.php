<?php

namespace App\Filament\Widgets;

use App\Models\Credit;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentCreditsTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Kredit Terbaru';
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                Credit::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk')
                    ->sortable(),

                Tables\Columns\TextColumn::make('principal_amount')
                    ->label('Nilai Kredit')
                    ->money('idr', true)
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'info' => 'PENDING',
                        'success' => 'ACTIVE',
                        'warning' => 'DUE',
                        'danger' => 'OVERDUE',
                        'gray' => 'CLOSED',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ]);
    }
}
