<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tamu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nik', 16)->unique();
            $table->string('email')->nullable();
            $table->string('no_hp', 20);
            $table->text('alamat')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('kewarganegaraan')->default('Indonesia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tamu');
    }
};
