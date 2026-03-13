<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('social_media');

        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 30);          // youtube | tiktok | instagram
            $table->string('title', 150);             // e.g. "Modern Minimalist House Tour"
            $table->string('description', 300)->nullable(); // Short caption shown on card
            $table->string('thumbnail_url', 500)->nullable(); // storage/public path
            $table->string('content_url', 500);       // Link to the actual post/video
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_media');
    }
};
