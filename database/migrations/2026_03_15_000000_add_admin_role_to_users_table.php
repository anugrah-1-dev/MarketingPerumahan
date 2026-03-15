<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan nilai 'admin' ke ENUM role pada tabel users.
     * Sebelumnya enum hanya: ['super_admin', 'affiliate']
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'affiliate') NOT NULL DEFAULT 'affiliate'");
    }

    public function down(): void
    {
        // Ubah user dengan role 'admin' ke 'affiliate' sebelum rollback agar tidak error
        DB::statement("UPDATE users SET role = 'affiliate' WHERE role = 'admin'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'affiliate') NOT NULL DEFAULT 'affiliate'");
    }
};
