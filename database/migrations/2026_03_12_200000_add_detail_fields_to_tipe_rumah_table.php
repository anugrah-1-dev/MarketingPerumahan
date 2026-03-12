<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tipe_rumah', function (Blueprint $table) {
            $table->tinyInteger('kamar_tidur')->default(2)->after('luas_tanah');
            $table->tinyInteger('kamar_mandi')->default(1)->after('kamar_tidur');
            $table->tinyInteger('lantai')->default(1)->after('kamar_mandi');
            $table->string('sertifikat')->default('SHM')->after('lantai');
            $table->text('fasilitas')->nullable()->after('sertifikat'); // JSON string
        });
    }

    public function down(): void
    {
        Schema::table('tipe_rumah', function (Blueprint $table) {
            $table->dropColumn(['kamar_tidur', 'kamar_mandi', 'lantai', 'sertifikat', 'fasilitas']);
        });
    }
};
