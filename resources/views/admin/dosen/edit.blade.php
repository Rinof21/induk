@extends('layouts.admin')

@section('title', 'Edit Data Dosen')
@section('page_title', 'Perbarui Data Dosen Internal')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Perbarui Data Dosen</h3>
            <p class="text-xs text-slate-500">Edit informasi profil dosen: {{ $dosen->nama_lengkap }}</p>
        </div>
        <a href="{{ route('admin.dosen.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.dosen.update', $dosen) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Identitas Pokok & Kependudukan/Pajak -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-id-card mr-1.5"></i>
                    Identitas Pegawai & Kependudukan
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap (beserta gelar) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $dosen->nama_lengkap) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" value="{{ old('nip', $dosen->nip) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NUPTK / NIDN</label>
                        <input type="text" name="nuptk" value="{{ old('nuptk', $dosen->nuptk) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="nik" value="{{ old('nik', $dosen->nik) }}" maxlength="25"
                               placeholder="16 Digit NIK KTP"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NPWP (Nomor Pokok Wajib Pajak)</label>
                        <input type="text" name="npwp" value="{{ old('npwp', $dosen->npwp) }}" maxlength="30"
                               placeholder="Contoh: 00.000.000.0-000.000"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <!-- Section 2: Jabatan & Bagian Internal Fakultas -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-award mr-1.5"></i>
                    Jabatan Fungsional & Bagian Internal Fakultas
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Bagian Internal Fakultas <span class="text-rose-500">*</span></label>
                        <select name="bagian" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600">
                            @foreach($daftarBagian as $b)
                                <option value="{{ $b }}" {{ old('bagian', $dosen->bagian) == $b ? 'selected' : '' }}>{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jabatan Fungsional (Jafung) <span class="text-rose-500">*</span></label>
                        <select name="jafung" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            @foreach(['Tenaga Pengajar', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'] as $jf)
                                <option value="{{ $jf }}" {{ old('jafung', $dosen->jafung) == $jf ? 'selected' : '' }}>{{ $jf }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pangkat / Golongan Ruang</label>
                        <input type="text" name="pangkat_gol" value="{{ old('pangkat_gol', $dosen->pangkat_gol) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pendidikan Terakhir <span class="text-rose-500">*</span></label>
                        <select name="pendidikan_terakhir" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            @foreach(['S1', 'S2', 'S3', 'Sp-1', 'Sp-2', 'Subspesialis'] as $pend)
                                <option value="{{ $pend }}" {{ old('pendidikan_terakhir', $dosen->pendidikan_terakhir) == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Ikatan Kerja <span class="text-rose-500">*</span></label>
                        <select name="status_ikatan_kerja" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            @foreach(['Dosen PNS', 'Dosen PPPK', 'Dosen Tetap Non-PNS', 'Dosen Luar Biasa'] as $ikatan)
                                <option value="{{ $ikatan }}" {{ old('status_ikatan_kerja', $dosen->status_ikatan_kerja) == $ikatan ? 'selected' : '' }}>{{ $ikatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 3: Status Tugas & Kontak -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-address-book mr-1.5"></i>
                    Status Keaktifan & Kontak
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Keaktifan / Tugas <span class="text-rose-500">*</span></label>
                        <select name="status_tugas" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="Aktif" {{ old('status_tugas', $dosen->status_tugas) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tugas Belajar" {{ old('status_tugas', $dosen->status_tugas) == 'Tugas Belajar' ? 'selected' : '' }}>Tugas Belajar</option>
                            <option value="Izin Belajar" {{ old('status_tugas', $dosen->status_tugas) == 'Izin Belajar' ? 'selected' : '' }}>Izin Belajar</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email Dosen</label>
                        <input type="email" name="email" value="{{ old('email', $dosen->email) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $dosen->no_hp) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block font-bold text-slate-700 mb-1">Bidang Keahlian / Minat Riset</label>
                        <textarea name="bidang_keahlian" rows="2"
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">{{ old('bidang_keahlian', $dosen->bidang_keahlian) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.dosen.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/30 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
