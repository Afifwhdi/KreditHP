<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Migrasi nilai lama ke yang baru dulu biar aman
        DB::table('credits')->where('status', 'PENDING')->update(['status' => 'ACTIVE']);
        DB::table('credits')->where('status', 'BATAL')->update(['status' => 'TELAT']);

        // Ubah enum ke set baru
        DB::statement("ALTER TABLE credits MODIFY status ENUM('ACTIVE','LUNAS','TELAT') NOT NULL DEFAULT 'ACTIVE'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE credits MODIFY status ENUM('PENDING','ACTIVE','LUNAS','BATAL') NOT NULL DEFAULT 'PENDING'");
    }
};
