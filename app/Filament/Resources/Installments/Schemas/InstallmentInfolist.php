<?php

namespace App\Filament\Resources\Installments\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists;

class InstallmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Infolists\Components\TextEntry::make('credit.customer.name')
                ->label('Nama Pelanggan'),

            Infolists\Components\TextEntry::make('credit.customer.phone')
                ->label('Nomor HP'),

            Infolists\Components\TextEntry::make('credit.customer.address')
                ->label('Alamat')
                ->columnSpanFull(),

            Infolists\Components\TextEntry::make('credit.product.name')
                ->label('Produk HP'),

            Infolists\Components\TextEntry::make('credit.principal_amount')
                ->label('Harga HP')
                ->money('IDR'),

            Infolists\Components\TextEntry::make('credit.down_payment')
                ->label('DP')
                ->money('IDR'),

            Infolists\Components\TextEntry::make('credit.tenor_months')
                ->label('Tenor (bulan)'),

            Infolists\Components\TextEntry::make('credit.installment_amount')
                ->label('Angsuran per Bulan')
                ->money('IDR'),

            Infolists\Components\TextEntry::make('credit.target_amount')
                ->label('Total yang Harus Dibayar')
                ->money('IDR'),

            Infolists\Components\TextEntry::make('credit.start_date')
                ->label('Tanggal Mulai')
                ->date('d M Y'),

            Infolists\Components\TextEntry::make('credit.status')
                ->label('Status Kredit')
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
                }),

            Infolists\Components\RepeatableEntry::make('installments')
                ->label('Rekap Cicilan Bulanan')
                ->state(fn($record) => optional($record->credit)
                    ? $record->credit->installments()->orderBy('seq')->get()
                    : collect())
                ->schema([
                    Infolists\Components\TextEntry::make('seq')
                        ->label('Bulan')
                        ->formatStateUsing(fn($state) => "Bulan {$state}")
                        ->badge()
                        ->color('gray'),

                    Infolists\Components\TextEntry::make('due_date')
                        ->label('Jatuh Tempo')
                        ->date('d M Y'),

                    Infolists\Components\TextEntry::make('amount_due')
                        ->label('Nominal')
                        ->money('IDR'),

                    Infolists\Components\TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->icon(fn($state) => match ($state) {
                            'PAID'    => 'heroicon-o-check-circle',
                            'OVERDUE' => 'heroicon-o-x-circle',
                            'PARTIAL' => 'heroicon-o-clock',
                            'DUE'     => 'heroicon-o-clock',
                            default   => null,
                        })
                        ->color(fn($state) => match ($state) {
                            'PAID'    => 'success',
                            'PARTIAL' => 'warning',
                            'OVERDUE' => 'danger',
                            'DUE'     => 'gray',
                            default   => 'gray',
                        })
                        ->formatStateUsing(fn($state) => match ($state) {
                            'PAID'    => 'Sudah Bayar',
                            'OVERDUE' => 'Telat Bayar',
                            'PARTIAL' => 'Bayar Sebagian',
                            'DUE'     => 'Belum Bayar',
                            default   => $state,
                        }),
                ])
                ->grid(4)
                ->columnSpanFull(),
        ]);
    }
}
