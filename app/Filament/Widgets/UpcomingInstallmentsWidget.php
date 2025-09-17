<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Installment;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingInstallmentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Angsuran Jatuh Tempo (7 Hari ke Depan)';
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                Installment::query()
                    ->where('status', 'DUE')
                    ->whereBetween('due_date', [now(), Carbon::now()->addDays(7)])
                    ->orderBy('due_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('credit.customer.name')->label('Pelanggan')->sortable(),
                Tables\Columns\TextColumn::make('credit.product.name')->label('Produk')->sortable(),
                Tables\Columns\TextColumn::make('amount_due')
                    ->label('Jumlah')
                    ->money('idr', true),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y'),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'warning' => 'DUE',
                    'danger' => 'OVERDUE',
                    'success' => 'PAID',
                ]),
            ]);
    }
}
