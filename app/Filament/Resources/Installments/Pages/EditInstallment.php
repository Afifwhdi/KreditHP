<?php

namespace App\Filament\Resources\Installments\Pages;

use App\Filament\Resources\Installments\InstallmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditInstallment extends EditRecord
{
    protected static string $resource = InstallmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
