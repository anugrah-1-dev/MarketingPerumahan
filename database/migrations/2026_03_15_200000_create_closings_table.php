<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('closings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_data_id')->nullable()->constrained('client_data')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->foreignId('tipe_rumah_id')->nullable()->constrained('tipe_rumah')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->bigInteger('harga_jual')->default(0);
            $table->decimal('komisi_persen', 5, 2)->default(0);
            $table->bigInteger('komisi_nominal')->default(0);
            $table->enum('payment_status', ['dp', 'installment', 'paid-off'])->default('dp');
            $table->text('catatan')->nullable();
            $table->date('tanggal_closing');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('closings');
    }
};
