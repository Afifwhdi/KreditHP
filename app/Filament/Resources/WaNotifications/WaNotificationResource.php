<?php

namespace App\Filament\Resources\WaNotifications;

use App\Filament\Resources\WaNotifications\Pages\ListWaNotifications;
use App\Filament\Resources\WaNotifications\Schemas\WaNotificationForm;
use App\Filament\Resources\WaNotifications\Tables\WaNotificationsTable;
use App\Models\WaNotification;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class WaNotificationResource extends Resource
{
    protected static ?string $model = WaNotification::class;

    protected static string |BackedEnum| null $navigationIcon = 'heroicon-o-bell-alert';
    protected static string |UnitEnum| null $navigationGroup = 'Pengingat';
    protected static ?string $navigationLabel = 'Log Notifikasi';


    public static function form(Schema $schema): Schema
    {
        return WaNotificationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WaNotificationsTable::configure($table);
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

    public static function getPages(): array
    {
        return [
            'index' => ListWaNotifications::route('/'),
        ];
    }
}
