@extends('layouts.admin')

@section('title', 'Tambah Penguji Baru')
@section('page_title', 'Tambah Data Penguji')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Form Tambah Penguji Baru</h3>
            <p class="text-xs text-slate-500">Daftarkan dosen atau praktisi ahli sebagai penguji ujian</p>
        </div>
        <a href="{{ route('admin.penguji.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.penguji.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-id-badge mr-1.5"></i>
                    Identitas & Asal Institusi Penguji
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Penguji (beserta gelar) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                               placeholder="Contoh: dr. Hendra Kusuma, Sp.B, FICS"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NIP / NIDN (Bila ada)</label>
                        <input type="text" name="nip_nidn" value="{{ old('nip_nidn') }}"
                               placeholder="Contoh: 197803152005011004"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Unit Kerja / Asal Institusi / Rumah Sakit <span class="text-rose-500">*</span></label>
                        <input type="text" name="unit_kerja" value="{{ old('unit_kerja') }}" required
                               placeholder="Contoh: FK UNTAN / RSUD dr. Soedarso"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-certificate mr-1.5"></i>
                    Kualifikasi & Peran Penguji
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Penguji <span class="text-rose-500">*</span></label>
                        <select name="jenis_penguji" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                            @foreach(['Penguji OSCE', 'Penguji Sidang Profesi', 'Penguji Skripsi/Tesis', 'Penguji Eksternal', 'Penguji Uji Kompetensi'] as $jenis)
                                <option value="{{ $jenis }}" {{ old('jenis_penguji', 'Penguji OSCE') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Bidang Spesialisasi / Kepakaran</label>
                        <input type="text" name="spesialisasi" value="{{ old('spesialisasi') }}"
                               placeholder="Contoh: Ilmu Penyakit Dalam / Bedah"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. Sertifikat Pelatihan Penguji</label>
                        <input type="text" name="no_sertifikat" value="{{ old('no_sertifikat') }}"
                               placeholder="Contoh: OSCE-NAS-2024-089"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Keaktifan <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                            <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email Penguji</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="penguji@domain.com"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                               placeholder="Contoh: 081254320000"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block font-bold text-slate-700 mb-1">Keterangan / Catatan Penugasan Khusus</label>
                        <textarea name="keterangan" rows="2"
                                  placeholder="Contoh: Koordinator Stasi Pemeriksaan Fisik OSCE Nasional UKMPPD..."
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.penguji.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/30 transition">
                    Simpan Data Penguji
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

