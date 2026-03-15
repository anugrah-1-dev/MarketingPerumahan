<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_data', function (Blueprint $table) {
            $table->foreignId('tipe_rumah_id')->nullable()->after('bukti_pembayaran')
                  ->constrained('tipe_rumah')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('client_data', function (Blueprint $table) {
            $table->dropForeign(['tipe_rumah_id']);
            $table->dropColumn('tipe_rumah_id');
        });
    }
};
