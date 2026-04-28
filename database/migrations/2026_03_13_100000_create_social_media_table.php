<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 30);        // youtube | tiktok | instagram | other
            $table->string('nama', 100);           // Display name, e.g. "YouTube"
            $table->string('url_profil')->nullable(); // Channel / profile URL
            $table->text('deskripsi')->nullable();  // Short desc shown on card
            $table->integer('urutan')->default(0);  // Sort order
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_media');
    }
};
