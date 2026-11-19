@extends('layouts.admin')

@section('title', 'Tambah Pasien Standar')
@section('page_title', 'Tambah Pasien Standar (PS)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Form Tambah Pasien Standar</h3>
            <p class="text-xs text-slate-500">Lengkapi data pribadi dan peran simulasi pasien standar OSCE</p>
        </div>
        <a href="{{ route('admin.pasien-standar.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.pasien-standar.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Identitas Pasien Standar -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-user-tag mr-1.5"></i>
                    Identitas Pokok & Fisik
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Pasien Standar <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode_ps" value="{{ old('kode_ps', $suggestedCode) }}" required
                               placeholder="Contoh: PS-007"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition uppercase">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Pasien Standar <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                               placeholder="Contoh: Ahmad Maulana"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600">
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Usia (Tahun)</label>
                        <input type="number" name="usia" value="{{ old('usia') }}" min="1" max="120"
                               placeholder="Contoh: 35"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tinggi Badan (cm)</label>
                        <input type="number" name="tinggi_badan" value="{{ old('tinggi_badan') }}" min="30" max="250"
                               placeholder="Contoh: 170"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Berat Badan (kg)</label>
                        <input type="number" name="berat_badan" value="{{ old('berat_badan') }}" min="10" max="250"
                               placeholder="Contoh: 65"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Ketersediaan <span class="text-rose-500">*</span></label>
                        <select name="status_ketersediaan" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600">
                            <option value="Tersedia" {{ old('status_ketersediaan', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia (Siap Bertugas)</option>
                            <option value="Sedang Bertugas" {{ old('status_ketersediaan') == 'Sedang Bertugas' ? 'selected' : '' }}>Sedang Bertugas</option>
                            <option value="Tidak Aktif" {{ old('status_ketersediaan') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif / Cuti</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Kemampuan Peran & Catatan Klinis -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-stethoscope mr-1.5"></i>
                    Kemampuan Peran Simulasi Klinis
                </h4>
                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Peran / Kasus Penyakit yang Dikuasai</label>
                        <textarea name="peran_dikuasai" rows="3"
                                  placeholder="Contoh: Simulasi Hipertensi dengan keluhan sakit kepala tengkuk, Nyeri Dada Khas Angina, Konseling Diabetes Mellitus..."
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition">{{ old('peran_dikuasai') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Catatan Khusus / Riwayat Pelatihan</label>
                        <textarea name="catatan_khusus" rows="2"
                                  placeholder="Contoh: Lulusan Pelatihan PS Nasional 2024, tidak bisa simulasi stasi injeksi..."
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition">{{ old('catatan_khusus') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 3: Kontak & Alamat -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-phone mr-1.5"></i>
                    Kontak & Domisili
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                               placeholder="Contoh: 081254000000"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pekerjaan / Aktivitas Sehari-hari</label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
                               placeholder="Contoh: Wiraswasta / Ibu Rumah Tangga / Mahasiswa"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}"
                               placeholder="Contoh: Jl. Ahmad Yani No. 12, Kota Pontianak"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.pasien-standar.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/30 transition">
                    Simpan Pasien Standar
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

