@extends('layouts.admin')

@section('title', 'Tambah Data Eksternal')
@section('page_title', 'Tambah Data Eksternal')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Form Tambah Data Eksternal</h3>
            <p class="text-xs text-slate-500">Lengkapi data profil pengajar / tenaga ahli dari institusi eksternal</p>
        </div>
        <a href="{{ route('admin.eksternal.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.eksternal.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Identitas Pokok & Kependudukan -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-id-card mr-1.5"></i>
                    Identitas Pegawai & Kependudukan
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap (beserta gelar) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                               placeholder="Contoh: Dr. dr. Budi Setiawan, Sp.B, FICS"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" value="{{ old('nip') }}"
                               placeholder="Contoh: 198001012005011001"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NUPTK / NIDN</label>
                        <input type="text" name="nuptk" value="{{ old('nuptk') }}"
                               placeholder="Contoh: 6454768669130252"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="25"
                               placeholder="16 Digit NIK KTP (Contoh: 617101...)"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NPWP (Nomor Pokok Wajib Pajak)</label>
                        <input type="text" name="npwp" value="{{ old('npwp') }}" maxlength="30"
                               placeholder="Contoh: 00.000.000.0-000.000"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <!-- Section 2: Kualifikasi & Asal Institusi -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-building-columns mr-1.5"></i>
                    Kualifikasi, Bagian & Asal Institusi
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Bagian Fakultas</label>
                        <select name="bagian" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">-- Pilih Bagian --</option>
                            @foreach($daftarBagian as $bag)
                                <option value="{{ $bag }}" {{ old('bagian') == $bag ? 'selected' : '' }}>{{ $bag }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Asal Institusi / Rumah Sakit / Instansi Luar</label>
                        <input type="text" name="asal_institusi" value="{{ old('asal_institusi') }}"
                               placeholder="Contoh: RSUD dr. Soedarso / RSJ Sungai Bangkong"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pangkat / Golongan Ruang</label>
                        <input type="text" name="pangkat_gol" value="{{ old('pangkat_gol') }}"
                               placeholder="Contoh: Pembina / IV/a"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pendidikan Terakhir <span class="text-rose-500">*</span></label>
                        <select name="pendidikan_terakhir" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            @foreach(['S1', 'S2', 'S3', 'Sp-1', 'Sp-2', 'Subspesialis'] as $pend)
                                <option value="{{ $pend }}" {{ old('pendidikan_terakhir', 'S2') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 3: Kontak & Bidang Keahlian -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-address-book mr-1.5"></i>
                    Kontak & Keahlian
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="nama@instansi.com"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                               placeholder="Contoh: 08123456789"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Bidang Keahlian / Minat Kepakaran</label>
                        <textarea name="bidang_keahlian" rows="2"
                                  placeholder="Contoh: Ilmu Bedah Laparoskopi, Toksikologi Forensik, dll..."
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">{{ old('bidang_keahlian') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.eksternal.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/30 transition">
                    Simpan Data Eksternal
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

