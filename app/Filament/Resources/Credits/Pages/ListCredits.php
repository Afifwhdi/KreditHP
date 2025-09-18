<?php

namespace App\Filament\Resources\Credits\Pages;

use App\Filament\Resources\Credits\CreditResource;
use App\Models\Credit;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListCredits extends ListRecords
{
    protected static string $resource = CreditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('Semua')
                ->modifyQueryUsing(fn($query) => $query)
                ->badge(Credit::count())
                ->badgeColor('gray'),

            Tab::make('Aktif')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'ACTIVE'))
                ->badge(Credit::query()->where('status', 'ACTIVE')->count())
                ->badgeColor('warning'),

            Tab::make('Lunas')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'LUNAS'))
                ->badge(Credit::query()->where('status', 'LUNAS')->count())
                ->badgeColor('success'),
        ];
    }
}
