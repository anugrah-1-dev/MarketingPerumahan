<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipe_rumah_id')->constrained('tipe_rumah')->onDelete('restrict');
            $table->string('nomor_unit', 30);
            $table->string('blok', 30)->nullable();
            $table->enum('status', ['tersedia', 'booking', 'terjual'])->default('tersedia');
            $table->unsignedBigInteger('harga_jual')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
