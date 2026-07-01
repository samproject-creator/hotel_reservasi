<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 20)->unique(); // BK-20240101-0001
            $table->foreignId('tamu_id')->constrained('tamu')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users'); // petugas yang input
            $table->date('tanggal_checkin');
            $table->date('tanggal_checkout');
            $table->integer('jumlah_tamu')->default(1);
            $table->enum('status', ['pending', 'confirmed', 'checkin', 'checkout', 'cancelled'])->default('pending');
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->decimal('uang_muka', 12, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
