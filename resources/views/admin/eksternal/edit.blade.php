@extends('layouts.admin')

@section('title', 'Edit Data Eksternal')
@section('page_title', 'Perbarui Data Eksternal')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Perbarui Data Eksternal</h3>
            <p class="text-xs text-slate-500">Edit data profil pengajar / tenaga ahli: {{ $eksternal->nama_lengkap }}</p>
        </div>
        <a href="{{ route('admin.eksternal.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.eksternal.update', $eksternal) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Identitas Pokok & Kependudukan -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 pb-2 border-b border-slate-100">
                    <i class="fa-solid fa-id-card mr-1.5"></i>
                    Identitas Pegawai & Kependudukan
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap (beserta gelar) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $eksternal->nama_lengkap) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" value="{{ old('nip', $eksternal->nip) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NUPTK / NIDN</label>
                        <input type="text" name="nuptk" value="{{ old('nuptk', $eksternal->nuptk) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="nik" value="{{ old('nik', $eksternal->nik) }}" maxlength="25"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NPWP (Nomor Pokok Wajib Pajak)</label>
                        <input type="text" name="npwp" value="{{ old('npwp', $eksternal->npwp) }}" maxlength="30"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <!-- Section 2: Kualifikasi, Bagian & Asal Institusi -->
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
                                <option value="{{ $bag }}" {{ old('bagian', $eksternal->bagian) == $bag ? 'selected' : '' }}>{{ $bag }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Asal Institusi / Rumah Sakit / Instansi Luar</label>
                        <input type="text" name="asal_institusi" value="{{ old('asal_institusi', $eksternal->asal_institusi) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pangkat / Golongan Ruang</label>
                        <input type="text" name="pangkat_gol" value="{{ old('pangkat_gol', $eksternal->pangkat_gol) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pendidikan Terakhir <span class="text-rose-500">*</span></label>
                        <select name="pendidikan_terakhir" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            @foreach(['S1', 'S2', 'S3', 'Sp-1', 'Sp-2', 'Subspesialis'] as $pend)
                                <option value="{{ $pend }}" {{ old('pendidikan_terakhir', $eksternal->pendidikan_terakhir) == $pend ? 'selected' : '' }}>{{ $pend }}</option>
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
                        <input type="email" name="email" value="{{ old('email', $eksternal->email) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $eksternal->no_hp) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Bidang Keahlian / Minat Kepakaran</label>
                        <textarea name="bidang_keahlian" rows="2"
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">{{ old('bidang_keahlian', $eksternal->bidang_keahlian) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.eksternal.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
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

