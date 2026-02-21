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
            $table->string('tipe');        // e.g. "36/72"
            $table->string('nama');        // e.g. "Dahlia"
            $table->integer('kamar_tidur');
            $table->integer('kamar_mandi');
            $table->integer('luas_tanah');
            $table->bigInteger('harga');
            $table->enum('status', ['tersedia', 'booking', 'terjual'])->default('tersedia');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
