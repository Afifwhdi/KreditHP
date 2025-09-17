<?php

namespace App\Filament\Resources\Installments;

use App\Filament\Resources\Installments\Pages;
use App\Filament\Resources\Installments\Tables\InstallmentsTable;
use App\Filament\Resources\Installments\Schemas\InstallmentForm;
use App\Filament\Resources\Installments\Schemas\InstallmentInfolist;
use App\Models\Installment;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;

class InstallmentResource extends Resource
{
    protected static ?string $model = Installment::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-credit-card';
    protected static string | UnitEnum | null $navigationGroup = 'Transaksi Kredit';
    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return InstallmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstallmentsTable::configure($table);
    }

    public static function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return InstallmentInfolist::configure($schema);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['credit', 'credit.customer', 'credit.product']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()
            ->where('seq', 1)
            ->count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstallments::route('/'),
            'view' => Pages\ViewInstallment::route('/{record}'),
            'edit' => Pages\EditInstallment::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return 'Cicilan';
    }

    public static function getPluralLabel(): string
    {
        return 'Daftar Cicilan';
    }
}
