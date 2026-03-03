<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wa_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->string('agent_slug')->nullable();      // slug agent saat klik terjadi
            $table->string('ip_address', 45)->nullable();
            $table->string('device', 30)->nullable();      // Mobile / Desktop
            $table->string('browser', 80)->nullable();
            $table->string('page_url')->nullable();        // halaman asal klik
            $table->enum('status', ['new','follow-up','interested','not-interested','closed'])->default('new');
            $table->text('notes')->nullable();
            $table->dateTime('follow_up_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_clicks');
    }
};
