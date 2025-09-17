<?php

namespace App\Filament\Resources\Credits;

use App\Filament\Resources\Credits\Pages\CreateCredit;
use App\Filament\Resources\Credits\Pages\EditCredit;
use App\Filament\Resources\Credits\Pages\ListCredits;
use App\Filament\Resources\Credits\Schemas\CreditForm;
use App\Filament\Resources\Credits\Tables\CreditsTable;
use App\Models\Credit;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CreditResource extends Resource
{
    protected static ?string $model = Credit::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static string | UnitEnum | null $navigationGroup = 'Transaksi Kredit';

    public static function form(Schema $schema): Schema
    {
        return $schema->components(CreditForm::schema());
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function table(Table $table): Table
    {
        return CreditsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCredits::route('/'),
            // 'create' => CreateCredit::route('/create'),
            // 'edit' => EditCredit::route('/{record}/edit'),
        ];
    }
}
