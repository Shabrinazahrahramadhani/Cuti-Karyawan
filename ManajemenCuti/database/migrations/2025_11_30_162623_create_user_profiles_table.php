<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->integer('kuota_cuti')->default(12);
            $table->boolean('status_aktif')->default(true);
            $table->date('tanggal_bergabung')->nullable()->after('status_aktif');
            $table->string('foto')->nullable();
            $table->string('alamat')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->foreignId('divisi_id')->nullable()->constrained('divisions')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
