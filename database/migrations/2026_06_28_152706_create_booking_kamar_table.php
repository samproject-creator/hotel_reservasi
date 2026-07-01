<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel pivot Many-to-Many: satu booking bisa memesan banyak kamar
return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_kamar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking')->onDelete('cascade');
            $table->foreignId('kamar_id')->constrained('kamar')->onDelete('restrict');
            $table->decimal('harga_malam', 10, 2); // snapshot harga saat booking
            $table->integer('jumlah_malam');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->unique(['booking_id', 'kamar_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_kamar');
    }
};
