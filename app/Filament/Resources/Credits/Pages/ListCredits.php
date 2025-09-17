<?php

namespace App\Filament\Resources\Credits\Pages;

use App\Filament\Resources\Credits\CreditResource;
use App\Models\Credit;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

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
            'Aktif' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status', 'ACTIVE'))
                ->badge(Credit::query()->where('status', 'ACTIVE')->count())
                ->badgeColor('warning'),

            'Lunas' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status', 'LUNAS'))
                ->badge(Credit::query()->where('status', 'LUNAS')->count())
                ->badgeColor('success'),
        ];
    }
}
