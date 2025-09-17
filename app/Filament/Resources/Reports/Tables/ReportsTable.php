<?php

namespace App\Filament\Resources\Reports\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Forms;
use App\Models\Customer;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')->label('Pelanggan')->searchable(),
                TextColumn::make('credit.code')->label('Kode Kredit'),
                TextColumn::make('amount')->label('Nominal')->money('IDR'),
                TextColumn::make('paid_at')->label('Tanggal Bayar')->date('d M Y'),
            ])
            ->filters([])
            ->recordActions([

                Action::make('download_pelanggan')
                    ->label('Download Laporan Pelanggan')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        $customer = $record->customer;
                        $payments = $customer?->payments()->with('credit')->get();

                        $pdf = Pdf::loadView('reports.customer', [
                            'customer' => $customer,
                            'payments' => $payments,
                        ]);

                        return response()->streamDownload(
                            fn() => print($pdf->output()),
                            "laporan_{$customer->name}.pdf"
                        );
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),

                Action::make('laporanBulanan')
                    ->label('Export Laporan Bulanan')
                    ->icon('heroicon-o-document-text')
                    ->form([
                        Forms\Components\DatePicker::make('month')
                            ->label('Pilih Bulan')
                            ->displayFormat('F Y')
                            ->format('Y-m')
                            ->required(),

                        Forms\Components\Select::make('customer_id')
                            ->label('Pilih Pelanggan (Opsional)')
                            ->options(Customer::pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Semua Pelanggan'),
                    ])
                    ->action(function (array $data) {
                        $month = Carbon::parse($data['month']);

                        $query = Payment::query()
                            ->whereMonth('paid_at', $month->month)
                            ->whereYear('paid_at', $month->year)
                            ->with('customer', 'credit');

                        if (!empty($data['customer_id'])) {
                            $query->where('customer_id', $data['customer_id']);
                        }

                        $payments = $query->get();

                        $pdf = Pdf::loadView('reports.monthly', [
                            'month'    => $month->translatedFormat('F Y'),
                            'payments' => $payments,
                        ]);

                        return response()->streamDownload(
                            fn() => print($pdf->output()),
                            "laporan_bulanan.pdf"
                        );
                    }),
            ]);
    }
}
