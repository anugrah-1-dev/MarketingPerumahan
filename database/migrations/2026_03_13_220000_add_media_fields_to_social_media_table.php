<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_media', function (Blueprint $table) {
            $table->string('media_type', 20)->nullable()->after('content_url');
            $table->string('media_path', 500)->nullable()->after('media_type');
            $table->string('content_url', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('social_media', function (Blueprint $table) {
            $table->dropColumn(['media_type', 'media_path']);
            $table->string('content_url', 500)->nullable(false)->change();
        });
    }
};