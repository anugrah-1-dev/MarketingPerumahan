<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_data', function (Blueprint $table) {
            $table->enum('status_pembayaran', ['baru', 'dp', 'lunas', 'cancel'])
                  ->default('baru')
                  ->after('tipe_rumah_id');
        });
    }

    public function down(): void
    {
        Schema::table('client_data', function (Blueprint $table) {
            $table->dropColumn('status_pembayaran');
        });
    }
};
