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
        Schema::create('pengajuan_perubahans', function (Blueprint $table) {
            $table->id();
            $table->string('tipe', 50)->default('dosen'); // 'dosen', 'eksternal'
            $table->unsignedBigInteger('record_id')->nullable(); // ID data yang diajukan perubahan
            $table->string('nama_pengaju');
            $table->string('email_pengaju')->nullable();
            $table->string('no_hp_pengaju')->nullable();
            $table->text('alasan_perubahan')->nullable();
            $table->json('data_lama')->nullable(); // Snapshot data lama
            $table->json('data_baru'); // Data baru yang diajukan
            $table->string('dokumen_pendukung')->nullable(); // Path file bukti/dokumen
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['tipe', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_perubahans');
    }
};
