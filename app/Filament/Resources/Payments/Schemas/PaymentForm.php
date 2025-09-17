<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms;
use App\Models\Credit;
use App\Models\Customer;

class PaymentForm
{
    public static function schema(): array
    {
        return [
            // Pilih pelanggan (unik, tanpa duplikat)
            Forms\Components\Select::make('customer_id')
                ->label('Pelanggan Kredit')
                ->options(
                    fn() =>
                    Customer::whereHas('credits', fn($q) => $q->where('status', 'ACTIVE'))
                        ->pluck('name', 'id')
                )
                ->searchable()
                ->noSearchResultsMessage('Tidak ada hasil yang sesuai dengan pencarian Anda.')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    if (!$state) {
                        $set('credit_id', null);
                        $set('credit_code', null);
                        $set('product_name', null);
                        $set('product_price', null);
                        $set('installment_info', null);
                        $set('amount', 0);
                        return;
                    }

                    $credits = Credit::with(['product', 'installments'])
                        ->where('customer_id', $state)
                        ->where('status', 'ACTIVE')
                        ->get();

                    if ($credits->count() === 1) {
                        $credit = $credits->first();

                        // Auto set credit & detail
                        $set('credit_id', $credit->id);
                        $set('credit_code', $credit->code);
                        $set('product_name', $credit->product->name);
                        $set('product_price', $credit->product->price);

                        $installment = $credit->installments()
                            ->where('status', 'DUE')
                            ->orderBy('seq')
                            ->first();

                        if ($installment) {
                            $set('amount', $installment->amount_due);
                            $set('installment_info', "Cicilan bulan ke-{$installment->seq} dari {$credit->tenor_months}");
                        } else {
                            $set('installment_info', "Semua cicilan sudah lunas");
                        }
                    } else {
                        // Kalau lebih dari 1, biarkan admin pilih produk
                        $set('credit_id', null);
                        $set('credit_code', null);
                        $set('product_name', null);
                        $set('product_price', null);
                        $set('installment_info', null);
                        $set('amount', 0);
                    }
                }),

            // Pilih produk kredit (aktif hanya kalau customer punya >1 kredit)
            Forms\Components\Select::make('credit_id')
                ->label('Produk Kredit')
                ->options(function (callable $get) {
                    $customerId = $get('customer_id');
                    if (!$customerId) {
                        return [];
                    }

                    return Credit::with('product')
                        ->where('customer_id', $customerId)
                        ->where('status', 'ACTIVE')
                        ->get()
                        ->mapWithKeys(fn($credit) => [
                            $credit->id => $credit->product->name,
                        ]);
                })
                ->searchable()
                ->noSearchResultsMessage('Tidak ada hasil yang sesuai dengan pencarian Anda.')
                ->reactive()
                ->disabled(
                    fn(callable $get) =>
                    $get('customer_id')
                        && Credit::where('customer_id', $get('customer_id'))->where('status', 'ACTIVE')->count() === 1
                )
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state) {
                        $credit = Credit::with(['product', 'installments'])->find($state);

                        $set('credit_code', $credit->code);
                        $set('product_name', $credit->product->name);
                        $set('product_price', $credit->product->price);

                        $installment = $credit->installments()
                            ->where('status', 'DUE')
                            ->orderBy('seq')
                            ->first();

                        if ($installment) {
                            $set('amount', $installment->amount_due);
                            $set('installment_info', "Cicilan bulan ke-{$installment->seq} dari {$credit->tenor_months}");
                        } else {
                            $set('installment_info', "Semua cicilan sudah lunas");
                        }
                    }
                }),

            // Hidden credit_id untuk ikut tersimpan
            Forms\Components\Hidden::make('credit_id')
                ->dehydrated(true),

            // Info Kredit (readonly)
            Forms\Components\TextInput::make('credit_code')
                ->label('Kode Kredit')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('product_name')
                ->label('Produk')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('product_price')
                ->label('Harga Produk')
                ->prefix('Rp')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('installment_info')
                ->label('Info Cicilan')
                ->disabled()
                ->dehydrated(false),

            // Nominal Bayar
            Forms\Components\TextInput::make('amount')
                ->label('Nominal Bayar')
                ->numeric()
                ->required()
                ->disabled()
                ->dehydrated(true),

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
