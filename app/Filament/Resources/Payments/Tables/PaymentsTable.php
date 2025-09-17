<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class PaymentsTable
{
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('credit.code')
                    ->label('Kode Kredit')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('evidence_path')
                    ->label('Bukti')
                    ->square()
                    ->size(60)
                    ->disk('public')
                    ->visibility('public')
                    ->defaultImageUrl(url('/images/no-image.png')),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('method')
                    ->label('Metode Bayar')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal Bayar')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Tanggal Bayar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('paid_at')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('paid_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('paid_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = Indicator::make(
                                'Bayar dari ' . Carbon::parse($data['from'])->toFormattedDateString()
                            )->removeField('from');
                        }

                        if ($data['until'] ?? null) {
                            $indicators[] = Indicator::make(
                                'Bayar sampai ' . Carbon::parse($data['until'])->toFormattedDateString()
                            )->removeField('until');
                        }

                        return $indicators;
                    }),

                Filter::make('method')
                    ->form([
                        \Filament\Forms\Components\Select::make('value')
                            ->label('Metode Bayar')
                            ->options([
                                'CASH'     => 'Tunai',
                                'TRANSFER' => 'Transfer',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn(Builder $query, $value): Builder => $query->where('method', $value),
                        );
                    })
                    ->indicateUsing(function (array $data): array {
                        return match ($data['value'] ?? null) {
                            'CASH'     => [Indicator::make('Metode: Tunai')->removeField('value')],
                            'TRANSFER' => [Indicator::make('Metode: Transfer')->removeField('value')],
                            default    => [],
                        };
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
