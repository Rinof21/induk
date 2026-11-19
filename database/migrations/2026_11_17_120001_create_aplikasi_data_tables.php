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
        // Tambahan field pada tabel users jika belum ada
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'fakultas')) {
                $table->string('fakultas')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp')->nullable()->after('fakultas');
            }
        });

        // Tabel Dosen
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nip')->nullable()->index();
            $table->string('nuptk')->nullable()->index();
            $table->string('pangkat_gol')->nullable(); // contoh: Penata / III/c
            $table->string('jafung')->default('Tenaga Pengajar'); // Tenaga Pengajar, Asisten Ahli, Lektor, Lektor Kepala, Guru Besar
            $table->string('pendidikan_terakhir')->default('S2'); // S1, S2, S3, Sp-1, Sp-2, Subspesialis
            $table->string('unit_kerja'); // Fakultas Kedokteran, Fakultas Hukum, dll
            $table->string('program_studi')->nullable();
            $table->string('status_ikatan_kerja')->default('Dosen PNS'); // Dosen PNS, Dosen PPPK, Dosen Tetap Non-PNS, Dosen Luar Biasa
            $table->string('status_tugas')->default('Aktif'); // Aktif, Tugas Belajar, Izin Belajar
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('bidang_keahlian')->nullable();
            $table->timestamps();
        });

        // Tabel Penguji (Penguji OSCE / Sidang / Ujian Kompetensi / Skripsi / Tesis)
        Schema::create('pengujis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nip_nidn')->nullable()->index();
            $table->string('unit_kerja'); // Fakultas / Asal Institusi / Rumah Sakit
            $table->string('spesialisasi')->nullable(); // Penyakit Dalam, Bedah, Farmakologi, dll
            $table->string('jenis_penguji')->default('Penguji OSCE'); // Penguji OSCE, Penguji Skripsi/Tesis, Penguji Sidang Profesi, Penguji Eksternal
            $table->string('no_sertifikat')->nullable();
            $table->string('status')->default('Aktif'); // Aktif, Tidak Aktif
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel Pasien Standar (Standardized Patients)
        Schema::create('pasien_standars', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ps')->unique(); // contoh: PS-001
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->default('Laki-laki');
            $table->date('tanggal_lahir')->nullable();
            $table->integer('usia')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->text('peran_dikuasai')->nullable(); // Simulasi Kasus Hipertensi, Dispnea, dsb
            $table->integer('tinggi_badan')->nullable(); // dalam cm
            $table->integer('berat_badan')->nullable(); // dalam kg
            $table->string('status_ketersediaan')->default('Tersedia'); // Tersedia, Sedang Bertugas, Tidak Aktif
            $table->text('catatan_khusus')->nullable();
            $table->timestamps();
        });

        // Tabel Pengaturan Akses Publik & Sistem
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
        Schema::dropIfExists('pasien_standars');
        Schema::dropIfExists('pengujis');
        Schema::dropIfExists('dosens');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'fakultas')) {
                $table->dropColumn('fakultas');
            }
            if (Schema::hasColumn('users', 'no_hp')) {
                $table->dropColumn('no_hp');
            }
        });
    }
};
