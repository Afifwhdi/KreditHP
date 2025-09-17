<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms;
use App\Models\Credit;

class PaymentForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\Select::make('customer_id')
                ->relationship('customer', 'name')
                ->label('Pelanggan')
                ->searchable()
                ->preload()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state) {
                        $credit = \App\Models\Credit::where('customer_id', $state)->first();

                        if (!$credit) {
                            \Filament\Notifications\Notification::make()
                                ->title('Pelanggan tidak memiliki kredit')
                                ->body('Pelanggan yang dipilih belum terdaftar mengambil kredit HP.')
                                ->danger()
                                ->send();

                            $set('credit_id', null);
                            $set('credit_code', null);
                            $set('amount', 0);
                            return;
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Pelanggan valid')
                            ->body("Pelanggan memiliki kredit dengan kode {$credit->code}.")
                            ->success()
                            ->send();

                        $set('credit_id', $credit->id);
                        $set('credit_code', $credit->code);

                        $installment = $credit->installments()
                            ->where('status', 'DUE')
                            ->orderBy('seq')
                            ->first();

                        if ($installment) {
                            $set('amount', $installment->amount_due);
                        }
                    }
                }),


            Forms\Components\Hidden::make('credit_id'),

            Forms\Components\TextInput::make('credit_code')
                ->label('Kode Kredit')
                ->disabled()
                ->dehydrated(false)
                ->reactive()
                ->afterStateHydrated(function ($state, callable $get, callable $set) {
                    if ($get('credit_id')) {
                        $credit = Credit::find($get('credit_id'));
                        if ($credit) {
                            $set('credit_code', $credit->code);
                        }
                    }
                }),

            Forms\Components\TextInput::make('amount')
                ->label('Nominal Bayar')
                ->numeric()
                ->required()
                ->disabled()
                ->dehydrated(true)
                ->rule(function (callable $get) {
                    return function (string $attribute, $value, $fail) use ($get) {
                        $creditId = $get('credit_id');
                        if ($creditId) {
                            $credit = Credit::find($creditId);
                            $installment = $credit?->installments()
                                ->where('status', 'DUE')
                                ->orderBy('seq')
                                ->first();

                            $expected = $installment?->amount_due ?? 0;

                            if ($value != $expected) {
                                $fail("Nominal bayar harus tepat Rp " . number_format($expected, 0, ',', '.'));
                            }
                        }
                    };
                }),

            Forms\Components\Select::make('method')
                ->label('Metode Bayar')
                ->options([
                    'CASH'     => 'Tunai',
                    'TRANSFER' => 'Transfer',
                ])
                ->required(),

            Forms\Components\DateTimePicker::make('paid_at')
                ->label('Tanggal Bayar')
                ->required(),

            Forms\Components\FileUpload::make('evidence_path')
                ->label('Bukti Pembayaran')
                ->disk('public')
                ->directory('payments/evidence')
                ->nullable(),
        ];
    }
}
