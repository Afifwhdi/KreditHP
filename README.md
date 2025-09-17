![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-4.x-06B6D4?logo=tailwindcss&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?logo=mysql&logoColor=white)

# 💳 KreditHP - Sistem Kredit HP

Sistem Kredit HP adalah aplikasi berbasis **Laravel + Filament** yang saya kembangkan untuk mengelola proses kredit smartphone.  
Aplikasi ini membantu admin dalam mencatat pelanggan, mengelola cicilan, memantau status pembayaran, serta mengirimkan notifikasi otomatis jatuh tempo via WhatsApp.

---

## 🚀 Fitur Utama

-   **Manajemen Pelanggan**
    -   Simpan data customer lengkap (nama, alamat, KTP, nomor WhatsApp).
    -   Upload KTP otomatis tersimpan ke sistem.
-   **Manajemen Kredit**
    -   Catat HP yang dikreditkan, harga, dan durasi kredit (6–10 bulan).
-   **Rekap Pembayaran**
    -   Tracking cicilan bulanan dengan status: **LUNAS**, **BELUM LUNAS**, atau **OVERDUE**.
-   **Notifikasi WhatsApp**
    -   Kirim pesan otomatis H-1 sebelum jatuh tempo melalui integrasi WhatsApp Gateway (Venom Bot / Fontte API).
-   **Laporan & Export**
    -   Rekap pembayaran dapat diexport ke PDF per pelanggan.
-   **Admin Panel Modern**
    -   Dibangun menggunakan **Laravel Filament 4**, dengan tampilan responsif, elegan, dan mudah digunakan.

---

## 🛠️ Teknologi yang Digunakan

-   **Backend**: [Laravel 12](https://laravel.com/)
-   **Admin Panel**: [Filament 4](https://filamentphp.com/)
-   **Database**: MySQL / MariaDB
-   **Notifikasi WA**:Fontte API (integrasi opsional)
-   **Frontend**: Filament + Tailwind CSS
-   **Realtime**: Laravel WebSockets (opsional untuk notifikasi live)

---

## ⚙️ Cara Install Project

### 1. Clone Repository

```bash
git clone https://github.com/username/KreditHP.git
cd KreditHP
```
