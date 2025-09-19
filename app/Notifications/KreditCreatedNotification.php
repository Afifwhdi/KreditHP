<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class KreditCreatedNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Kredit baru berhasil dibuat',
            'body'  => 'Pelanggan A telah membuat kredit baru.',
        ];
    }

    public function toFilament(): FilamentNotification
    {
        return FilamentNotification::make()
            ->title('Kredit baru berhasil dibuat')
            ->body('Pelanggan A telah membuat kredit baru.')
            ->success();
    }
}
