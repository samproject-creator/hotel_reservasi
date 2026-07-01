<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('checkout', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('booking')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users');
            $table->dateTime('waktu_checkout');
            $table->decimal('total_tagihan', 12, 2);
            $table->decimal('biaya_tambahan', 10, 2)->default(0); // denda, laundry, dll
            $table->text('keterangan_biaya')->nullable();
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'kartu_kredit', 'debit'])->default('cash');
            $table->decimal('total_bayar', 12, 2);
            $table->decimal('kembalian', 10, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout');
    }
};
