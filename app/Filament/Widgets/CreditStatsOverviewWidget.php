<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Credit;
use App\Models\Payment;
use App\Models\Installment;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class CreditStatsOverviewWidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;
    protected static ?int $sort = 2;

    protected function getDescription(): ?string
    {
        return 'Ringkasan kredit, pembayaran, dan status angsuran';
    }

    protected function getHeading(): ?string
    {
        return 'Statistik Kredit HP';
    }

    protected function getStats(): array
    {
        $filter = $this->filters['range'] ?? 'today';

        if ($filter === 'custom') {
            $startDate = !is_null($this->filters['startDate'] ?? null)
                ? Carbon::parse($this->filters['startDate'])
                : now()->startOfDay();
            $endDate = !is_null($this->filters['endDate'] ?? null)
                ? Carbon::parse($this->filters['endDate'])->endOfDay()
                : now()->endOfDay();
        } else {
            [$startDate, $endDate] = match ($filter) {
                'today' => [now()->startOfDay(), now()->endOfDay()],
                'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
                'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
                'this_year' => [now()->startOfYear(), now()->endOfYear()],
                default => [now()->startOfDay(), now()->endOfDay()],
            };
        }

        // Query data sesuai periode
        $credits = Credit::whereBetween('created_at', [$startDate, $endDate])->get();
        $payments = Payment::whereBetween('paid_at', [$startDate, $endDate])->get();
        $installments = Installment::whereBetween('due_date', [$startDate, $endDate])->get();

        // Hitung
        $kreditAktif = $credits->whereIn('status', ['ACTIVE', 'PENDING'])->count();
        $totalPembayaran = $payments->sum('amount') ?? 0;
        $angsuranDue = $installments->where('status', 'DUE')->sum('amount_due') ?? 0;
        $angsuranOverdue = $installments->where('status', 'OVERDUE')->sum('amount_due') ?? 0;

        return [
            Stat::make('Kredit Aktif', $kreditAktif)
                ->description('Jumlah kredit berjalan')
                ->descriptionIcon('heroicon-o-credit-card', IconPosition::Before)
                ->chart($credits->countBy(fn() => 'total')->values()->toArray())
                ->color('info'),

            Stat::make('Pembayaran Masuk', 'Rp ' . number_format($totalPembayaran, 0, ",", "."))
                ->description('Total pembayaran masuk')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::Before)
                ->chart($payments->pluck('amount')->toArray())
                ->color('success'),

            Stat::make('Angsuran Jatuh Tempo', 'Rp ' . number_format($angsuranDue, 0, ",", "."))
                ->description('Total angsuran DUE')
                ->descriptionIcon('heroicon-o-exclamation-triangle', IconPosition::Before)
                ->chart($installments->where('status', 'DUE')->pluck('amount_due')->toArray())
                ->color('warning'),

            Stat::make('Angsuran Tertunggak', 'Rp ' . number_format($angsuranOverdue, 0, ",", "."))
                ->description('Total angsuran OVERDUE')
                ->descriptionIcon('heroicon-o-x-circle', IconPosition::Before)
                ->chart($installments->where('status', 'OVERDUE')->pluck('amount_due')->toArray())
                ->color('danger'),
        ];
    }
}
