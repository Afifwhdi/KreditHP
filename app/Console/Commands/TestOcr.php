<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use thiagoalessio\TesseractOCR\TesseractOCR;

class TestOcr extends Command
{
    protected $signature = 'ocr:test {path}';
    protected $description = 'Test OCR pada file gambar KTP';

    public function handle()
    {
        $path = $this->argument('path');

        if (!file_exists($path)) {
            $this->error("❌ File tidak ditemukan: {$path}");
            return Command::FAILURE;
        }

        try {
            $text = (new TesseractOCR($path))
                ->executable('C:/Program Files/Tesseract-OCR/tesseract.exe')
                ->lang('ind')
                ->run();

            $this->info("✅ OCR hasil:");
            $this->line($text);

            preg_match('/\b\d{16}\b/', $text, $matches);
            if (!empty($matches)) {
                $this->info("🎯 NIK terdeteksi: " . $matches[0]);
            } else {
                $this->warn("⚠️ Tidak ada NIK 16 digit ditemukan, coba isi manual.");
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
