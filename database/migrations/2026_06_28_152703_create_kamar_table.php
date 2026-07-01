<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kamar', 10)->unique();
            $table->foreignId('tipe_kamar_id')->constrained('tipe_kamar')->onDelete('restrict');
            $table->integer('lantai');
            $table->enum('status', ['tersedia', 'ditempati', 'maintenance'])->default('tersedia');
            $table->text('keterangan')->nullable();
            $table->text('images')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
