<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);                    // Nama lengkap agent
            $table->string('jabatan', 100)->default('Marketing Executive'); // Jabatan
            $table->string('slug', 50)->unique();           // URL: /anugrah, /fajar, dll
            $table->boolean('aktif')->default(true);        // On/off tanpa hapus data
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
