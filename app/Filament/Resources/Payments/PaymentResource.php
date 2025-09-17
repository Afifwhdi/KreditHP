<?php

namespace App\Filament\Resources\Payments;

use App\Filament\Resources\Payments\Pages\ListPayments;
use App\Filament\Resources\Payments\Schemas\PaymentForm;
use App\Filament\Resources\Payments\Tables\PaymentsTable;
use App\Models\Payment;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
    protected static string |UnitEnum| null $navigationGroup = 'Transaksi Kredit';

    public static function form(Schema $schema): Schema
    {
        return $schema->components(PaymentForm::schema());
    }

    public static function table(Table $table): Table
    {
        return PaymentsTable::table($table);
    }


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function beforeCreate($record): void
    {
        if ($record->credit->status === 'LUNAS') {
            throw new \Exception("❌ Kredit sudah lunas, tidak bisa menambahkan pembayaran baru.");
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
            // 'create' => CreatePayment::route('/create'),
            // 'edit' => EditPayment::route('/{record}/edit'),
        ];
    }
}
