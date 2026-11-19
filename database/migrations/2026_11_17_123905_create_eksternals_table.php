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
        Schema::create('eksternals', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nip')->nullable()->index();
            $table->string('nuptk')->nullable()->index();
            $table->string('nik')->nullable()->index();
            $table->string('npwp')->nullable()->index();
            $table->string('asal_institusi')->nullable(); // Asal instansi / RS / Universitas luar
            $table->string('pangkat_gol')->nullable();
            $table->string('pendidikan_terakhir')->default('S2'); // S1, S2, S3, Sp-1, Sp-2, Subspesialis
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('bidang_keahlian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eksternals');
    }
};
