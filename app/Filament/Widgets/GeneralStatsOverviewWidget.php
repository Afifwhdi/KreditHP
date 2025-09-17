<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Credit;
use App\Models\Payment;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class GeneralStatsOverviewWidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;
    protected static ?int $sort = 1;

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

        $customers = Customer::whereBetween('created_at', [$startDate, $endDate])->get();
        $products = Product::whereBetween('created_at', [$startDate, $endDate])->get();
        $credits = Credit::whereBetween('created_at', [$startDate, $endDate])->get();
        $payments = Payment::whereBetween('paid_at', [$startDate, $endDate])->get();

        return [
            Stat::make('Total Pelanggan', Customer::count())
                ->description('Pelanggan terdaftar')
                ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                ->chart($customers->countBy(fn() => 'total')->values()->toArray())
                ->color('primary'),

            Stat::make('Total HP', Product::count())
                ->description('Produk tersedia')
                ->descriptionIcon('heroicon-o-device-phone-mobile', IconPosition::Before)
                ->chart($products->countBy(fn() => 'total')->values()->toArray())
                ->color('info'),

            Stat::make('Kredit Baru', 'Rp ' . number_format($credits->sum('principal_amount'), 0, ",", "."))
                ->description('Total nilai kredit periode ini')
                ->descriptionIcon('heroicon-o-document-text', IconPosition::Before)
                ->chart($credits->pluck('principal_amount')->toArray())
                ->color('warning'),

        ];
    }
}
