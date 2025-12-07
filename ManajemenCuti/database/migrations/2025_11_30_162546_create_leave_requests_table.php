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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('leader_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->enum('jenis_cuti', ['Tahunan', 'Sakit']);
           $table->date('tanggal_pengajuan')->nullable()->after('jenis_cuti');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('total_hari');
            $table->text('alasan');
            $table->text('alasan_pembatalan')->nullable();
            $table->string('surat_dokter')->nullable();
            $table->text('alamat_cuti')->nullable();
            $table->string('nomor_darurat')->nullable();
            $table->enum('status', ['Pending', 'Approved by Leader', 'Approved', 'Rejected', 'Cancelled'])->default('Pending');
            $table->timestamps();
            $table->index('status');
            $table->text('catatan_penolakan')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn(['tanggal_pengajuan']);
        });

        Schema::dropIfExists('leave_requests');
    }
};
