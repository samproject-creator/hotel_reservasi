<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel activity_logs menyimpan semua aktivitas CRUD yang dilakukan user.
 * Digunakan sebagai fitur Activity Log / Audit Trail (nilai tambahan tugas besar).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Siapa yang melakukan aksi
            $table->foreignId('user_id')
                  ->nullable()           // nullable untuk aksi sistem / guest
                  ->constrained('users')
                  ->onDelete('set null');

            // Nama aksi (created, updated, deleted, login, logout, export, dll)
            $table->string('action', 50);

            // Modul/tabel yang terdampak (booking, kamar, tamu, dll)
            $table->string('module', 50);

            // ID record yang terdampak (nullable untuk aksi global seperti login)
            $table->unsignedBigInteger('record_id')->nullable();

            // Deskripsi aksi yang mudah dibaca manusia
            $table->string('description', 255);

            // Snapshot data sebelum dan sesudah perubahan (untuk audit trail)
            $table->json('old_data')->nullable(); // data sebelum update/delete
            $table->json('new_data')->nullable(); // data setelah create/update

            // Metadata request
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 512)->nullable();

            $table->timestamp('created_at')->useCurrent();
            // Tidak perlu updated_at karena log bersifat immutable

            // Index untuk performa query filter
            $table->index(['user_id', 'created_at']);
            $table->index(['module', 'action']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
