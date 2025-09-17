<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MessageTemplate;

class MessageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Reminder H-1',
                'content' => "Halo {{name}},\n\nKami ingin mengingatkan bahwa cicilan ke-{{month}} untuk produk {{product}} sebesar Rp {{amount}} akan jatuh tempo pada {{due_date}}.\n\nMohon agar Bapak/Ibu dapat melakukan pembayaran tepat waktu. Terima kasih atas kerja samanya. 🙏",
                'is_active' => true,
            ],
            [
                'name' => 'Telat H+1',
                'content' => "Halo {{name}},\n\nKami mencatat bahwa cicilan ke-{{month}} untuk produk {{product}} sebesar Rp {{amount}} dengan jatuh tempo {{due_date}} belum kami terima.\n\nMohon agar pembayaran segera dilakukan agar status kredit Bapak/Ibu tetap lancar. Terima kasih atas perhatian dan kerja samanya. 🙏",
                'is_active' => true,
            ],
            [
                'name' => 'Terima Kasih',
                'content' => "Halo {{name}},\n\nTerima kasih telah melakukan pembayaran cicilan ke-{{month}} sebesar Rp {{amount}} untuk produk {{product}} pada tanggal {{due_date}}.\n\nKami sangat menghargai kerja sama Bapak/Ibu. Semoga hari Anda menyenangkan. 🙏",
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            MessageTemplate::updateOrCreate(['name' => $template['name']], $template);
        }
    }
}
