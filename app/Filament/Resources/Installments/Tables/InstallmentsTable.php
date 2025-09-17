<?php

namespace App\Filament\Resources\Installments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InstallmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('seq', 1))
            ->columns([
                TextColumn::make('credit.customer.name')
                    ->label('Nama Pelanggan')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('credit.product.name')
                    ->label('Produk HP'),

                TextColumn::make('bulan_aktif')
                    ->label('Bulan')
                    ->getStateUsing(function ($record) {
                        $cicilan = $record->credit?->installments()
                            ->whereIn('status', ['DUE', 'OVERDUE'])
                            ->orderBy('seq')
                            ->first();

                        if (!$cicilan) {
                            return 'Lunas';
                        }

                        $statusText = $cicilan->status === 'OVERDUE'
                            ? 'Telat'
                            : 'Belum Lunas';

                        return "Bulan {$cicilan->seq} : {$statusText}";
                    })
                    ->badge()
                    ->color(function ($record) {
                        $cicilan = $record->credit?->installments()
                            ->whereIn('status', ['DUE', 'OVERDUE'])
                            ->orderBy('seq')
                            ->first();

                        if (!$cicilan) {
                            return 'success';
                        }

                        return match ($cicilan->status) {
                            'OVERDUE' => 'danger',
                            'DUE'     => 'warning',
                            default   => 'gray',
                        };
                    }),

                TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo Berikutnya')
                    ->getStateUsing(function ($record) {
                        $cicilan = $record->credit?->installments()
                            ->whereIn('status', ['DUE', 'OVERDUE'])
                            ->orderBy('seq')
                            ->first();

                        return $cicilan?->due_date
                            ? $cicilan->due_date->format('d M Y')
                            : '-';
                    })
                    ->sortable(),

                TextColumn::make('amount_due')
                    ->label('Angsuran/Bulan')
                    ->money('IDR'),

                TextColumn::make('credit.status')
                    ->label('Status Kredit')
                    ->badge()
                    ->colors([
                        'success' => fn($state) => $state === 'ACTIVE',
                        'success' => fn($state) => $state === 'LUNAS',
                        'danger'  => fn($state) => $state === 'TELAT',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'ACTIVE' => 'Aktif',
                        'LUNAS'  => 'Lunas',
                        'TELAT'  => 'Telat',
                        default  => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('cicilan_status')
                    ->label('Status Cicilan')
                    ->options([
                        null       => 'Semua',
                        'PAID'     => 'Lunas',
                        'DUE'      => 'Belum Lunas',
                        'OVERDUE'  => 'Telat',
                    ])
                    ->query(function (Builder $query, $value) {
                        if ($value === 'PAID') {
                            $query->where('status', 'PAID');
                        } elseif ($value === 'DUE') {
                            $query->where('status', 'DUE');
                        } elseif ($value === 'OVERDUE') {
                            $query->where('status', 'OVERDUE');
                        }
                    }),

                Filter::make('hari_ini')
                    ->label('Jatuh Tempo Hari Ini')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->whereDate('due_date', now()->toDateString())
                    )
                    ->toggle(),
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
