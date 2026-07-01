<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipe_kamar', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tipe');            // Standard, Deluxe, Suite
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_per_malam', 10, 2);
            $table->integer('kapasitas')->default(2);
            $table->json('fasilitas')->nullable();  // ['AC','TV','WiFi','Bathtub']
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipe_kamar');
    }
};
