<?php

namespace App\Filament\Resources\Payments\Tables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Indicator;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Action;

class PaymentsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('credit.code')
                    ->label('Kode Kredit')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('evidence_path')
                    ->label('Bukti')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(asset('images/no-image.png'))
                    ->tooltip(fn($record) => $record->evidence_path ? 'Klik untuk lihat bukti' : null)
                    ->url(fn($record) => $record->evidence_path ? asset('storage/' . $record->evidence_path) : null, true),

                TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('method')
                    ->label('Metode Bayar')
                    ->badge()
                    ->sortable()
                    ->icon(fn(string $state): ?string => match ($state) {
                        'CASH'     => 'heroicon-o-banknotes',
                        'TRANSFER' => 'heroicon-o-credit-card',
                        default    => null,
                    }),

                TextColumn::make('amount')
                    ->label('Nominal Bayar')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('paid_at')
                    ->label('Tanggal Bayar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('paid_at')
                    ->label('Tanggal Bayar')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari'),
                        DatePicker::make('until')
                            ->label('Sampai'),
                    ])
                    ->query(
                        fn(Builder $query, array $data): Builder => $query
                            ->when($data['from'], fn($q) => $q->whereDate('paid_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('paid_at', '<=', $data['until']))
                    )
                    ->indicateUsing(fn(array $data): array => [
                        ...(
                            $data['from']
                            ? [Indicator::make('Bayar dari ' . Carbon::parse($data['from'])->toFormattedDateString())->removeField('from')]
                            : []
                        ),
                        ...(
                            $data['until']
                            ? [Indicator::make('Bayar sampai ' . Carbon::parse($data['until'])->toFormattedDateString())->removeField('until')]
                            : []
                        ),
                    ]),

                SelectFilter::make('method')
                    ->label('Metode Bayar')
                    ->options([
                        'CASH'     => 'Tunai',
                        'TRANSFER' => 'Transfer',
                    ])
                    ->placeholder('Pilih metode')
                    ->indicateUsing(
                        fn($value): ?string => $value
                            ? "Metode: " . ($value === 'CASH' ? 'Tunai' : 'Transfer')
                            : null
                    ),
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter Data')
            )

            ->recordActions([
                ViewAction::make(),
                // DeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('paid_at', 'desc');
    }
}
