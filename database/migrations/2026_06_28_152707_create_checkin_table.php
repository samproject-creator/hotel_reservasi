<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('checkin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('booking')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users'); // petugas yang proses
            $table->dateTime('waktu_checkin');
            $table->string('no_identitas')->nullable();  // KTP/Paspor saat check-in
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkin');
    }
};
