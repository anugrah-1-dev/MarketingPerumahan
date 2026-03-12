<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipe_rumah_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipe_rumah_id')
                  ->constrained('tipe_rumah')
                  ->onDelete('cascade');
            $table->string('path');               // path file di storage
            $table->string('keterangan')->nullable(); // label foto (opsional)
            $table->unsignedSmallInteger('urutan')->default(0); // urutan tampil
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipe_rumah_foto');
    }
};
