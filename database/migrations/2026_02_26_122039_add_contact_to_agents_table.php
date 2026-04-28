<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->string('email', 150)->nullable()->after('slug');
            $table->string('phone', 20)->nullable()->after('email');
            $table->decimal('commission', 5, 2)->default(0)->after('phone'); // contoh: 2.50 = 2.5%
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['email', 'phone', 'commission']);
        });
    }
};
