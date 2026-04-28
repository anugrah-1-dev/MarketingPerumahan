<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wa_clicks', function (Blueprint $table) {
            // Kode referral affiliate (format BSA-XXXX) — nullable karena bisa dari landing umum
            $table->string('referral_code', 12)->nullable()->after('agent_slug')->index();
            // Siapa user_id affiliate-nya (FK ke users)
            $table->foreignId('affiliate_user_id')->nullable()->after('referral_code')
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('wa_clicks', function (Blueprint $table) {
            $table->dropForeign(['affiliate_user_id']);
            $table->dropColumn(['referral_code', 'affiliate_user_id']);
        });
    }
};
