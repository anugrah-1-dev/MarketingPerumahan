<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipe_rumah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tipe');           // e.g. "Tipe 36/72"
            $table->integer('luas_bangunan');      // dalam m²
            $table->integer('luas_tanah');         // dalam m²
            $table->bigInteger('harga');           // harga normal
            $table->bigInteger('harga_diskon')->nullable(); // harga setelah diskon
            $table->boolean('is_diskon')->default(false);   // tampil di homepage?
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();  // path file gambar
            $table->integer('stok_tersedia')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipe_rumah');
    }
};
