<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('closings', function (Blueprint $table) {
            $table->enum('komisi_status', ['pending', 'terbayar'])
                  ->default('pending')
                  ->after('catatan');
            $table->string('bukti_transfer')->nullable()->after('komisi_status');
        });
    }

    public function down(): void
    {
        Schema::table('closings', function (Blueprint $table) {
            $table->dropColumn(['komisi_status', 'bukti_transfer']);
        });
    }
};
