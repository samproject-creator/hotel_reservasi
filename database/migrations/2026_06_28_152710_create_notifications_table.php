<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel standar Laravel untuk menyimpan database notifications.
 * Digunakan oleh BookingConfirmedNotification dan CheckInReminderNotification
 * dengan channel 'database'.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');                     // Class notification
            $table->morphs('notifiable');               // user_id + user_type
            $table->text('data');                       // JSON payload
            $table->timestamp('read_at')->nullable();   // null = belum dibaca
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
