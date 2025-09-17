<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportsResource;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // \Filament\Actions\Action::make('export_all')
            //     ->label('Export Semua Pembayaran')
            //     ->action(function () {
            //         $payments = \App\Models\Payment::with('customer', 'credit')->get();
            //         $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.monthly', [
            //             'payments' => $payments,
            //             'month' => now()->translatedFormat('F Y'),
            //         ]);
            //         return response()->streamDownload(
            //             fn() => print($pdf->output()),
            //             "laporan_semua_pembayaran.pdf"
            //         );
            //     }),
        ];
    }
}
