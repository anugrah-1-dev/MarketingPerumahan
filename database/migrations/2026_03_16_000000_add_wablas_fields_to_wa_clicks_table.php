<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wa_clicks', function (Blueprint $table) {
            // Nomor HP pengirim yang diketahui dari webhook Wablas
            $table->string('sender_phone', 25)->nullable()->after('page_url')->index();
            // Nama kontak pengirim dari WhatsApp
            $table->string('sender_name', 100)->nullable()->after('sender_phone');
            // Pesan teks terakhir yang diterima dari pengunjung
            $table->text('last_message')->nullable()->after('sender_name');
            // Asal lead: website (klik tombol WA) atau wablas (pesan masuk)
            $table->enum('source', ['website', 'wablas'])->default('website')->after('last_message');
        });
    }

    public function down(): void
    {
        Schema::table('wa_clicks', function (Blueprint $table) {
            $table->dropIndex(['sender_phone']);
            $table->dropColumn(['sender_phone', 'sender_name', 'last_message', 'source']);
        });
    }
};
