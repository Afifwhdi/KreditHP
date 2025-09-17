<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';

    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }

    public function getTitle(): string
    {
        return 'Dashboard Admin';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Widgets\StatsOverview::class,
            // \App\Filament\Widgets\OverdueInstallmentsStatsWidget::class,
        ];
    }
}
