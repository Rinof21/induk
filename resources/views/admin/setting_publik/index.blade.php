@extends('layouts.admin')

@section('title', 'Pengaturan Akses Token Halaman Publik')
@section('page_title', 'Akses Token Halaman Publik')

@section('content')
<div class="max-w-4xl mx-auto space-y-8" x-data="{ copied: false }">

    <!-- Header Description -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Konfigurasi Akses Token Publik</h3>
            <p class="text-xs text-slate-500">Atur proteksi token untuk halaman publik dosen, penguji, dan pasien standar</p>
        </div>
        <form action="{{ route('admin.pengaturan-publik.toggle') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-sm {{ $tokenEnabled ? 'bg-amber-100 hover:bg-amber-200 text-amber-900' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                <i class="fa-solid {{ $tokenEnabled ? 'fa-toggle-on' : 'fa-toggle-off' }} text-base"></i>
                <span>{{ $tokenEnabled ? 'Klik untuk Nonaktifkan Token' : 'Klik untuk Aktifkan Token' }}</span>
            </button>
        </form>
    </div>

    <!-- Status Banner -->
    <div class="p-6 rounded-2xl border transition shadow-sm {{ $tokenEnabled ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-amber-50/70 border-amber-200 text-amber-900' }}">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shrink-0 {{ $tokenEnabled ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }}">
                <i class="fa-solid {{ $tokenEnabled ? 'fa-shield-halved' : 'fa-lock-open' }}"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center space-x-2">
                    <span class="font-extrabold text-sm uppercase tracking-wider">
                        Status Proteksi: {{ $tokenEnabled ? 'AKTIF (Terproteksi Token)' : 'NONAKTIF (Akses Publik Bebas)' }}
                    </span>
                </div>
                <p class="text-xs mt-1 leading-relaxed opacity-90">
                    @if($tokenEnabled)
                        Halaman publik saat ini <strong>dikunci</strong>. Seluruh bagian fakultas yang mengunjungi halaman publik harus memasukkan token akses atau membuka tautan dengan parameter token.
                    @else
                        Halaman publik saat ini <strong>terbuka bebas</strong>. Siapa saja dapat langsung melihat data dosen, penguji, dan pasien standar tanpa memasukkan token akses.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Kotak Tautan Ber-Token Langsung (Shareable Link) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h4 class="text-sm font-bold text-slate-900 mb-1 flex items-center">
            <i class="fa-solid fa-share-nodes text-blue-600 mr-2"></i>
            Tautan Akses Langsung Ber-Token (Shareable Link)
        </h4>
        <p class="text-xs text-slate-500 mb-4">
            Anda dapat membagikan tautan ini langsung ke civitas akademika atau bagian fakultas (misal melalui WhatsApp/Surat Resmi) agar mereka langsung masuk tanpa harus mengetik token secara manual:
        </p>

        <div class="flex items-center space-x-2">
            <input type="text" readonly id="shareableLinkInput" value="{{ $shareableUrl }}"
                   class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-700 select-all focus:outline-none">
            <button type="button" 
                    @click="navigator.clipboard.writeText(document.getElementById('shareableLinkInput').value); copied = true; setTimeout(() => copied = false, 2500)"
                    class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-sm">
                <i class="fa-solid" :class="copied ? 'fa-check' : 'fa-copy'"></i>
                <span x-text="copied ? 'Tersalin!' : 'Salin Tautan'"></span>
            </button>
            <a href="{{ $shareableUrl }}" target="_blank" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition" title="Uji Coba Buka di Tab Baru">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        </div>
    </div>

    <!-- Form Utama Pengaturan Token -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.pengaturan-publik.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Toggle Radio -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">
                    Status Proteksi Halaman Publik
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-start p-4 rounded-xl border cursor-pointer transition {{ $tokenEnabled ? 'border-emerald-500 bg-emerald-50/30 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:bg-slate-50' }}">
                        <input type="radio" name="public_token_enabled" value="1" {{ $tokenEnabled ? 'checked' : '' }} class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                        <div class="ml-3">
                            <span class="block text-xs font-bold text-slate-900">Aktifkan Proteksi Token</span>
                            <span class="block text-[11px] text-slate-500 mt-0.5">Pengunjung harus memasukkan token untuk melihat data.</span>
                        </div>
                    </label>

                    <label class="flex items-start p-4 rounded-xl border cursor-pointer transition {{ !$tokenEnabled ? 'border-amber-500 bg-amber-50/30 ring-2 ring-amber-500/20' : 'border-slate-200 hover:bg-slate-50' }}">
                        <input type="radio" name="public_token_enabled" value="0" {{ !$tokenEnabled ? 'checked' : '' }} class="mt-0.5 text-amber-600 focus:ring-amber-500">
                        <div class="ml-3">
                            <span class="block text-xs font-bold text-slate-900">Nonaktifkan Token (Terbuka Bebas)</span>
                            <span class="block text-[11px] text-slate-500 mt-0.5">Semua orang/fakultas dapat melihat data tanpa token.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Token Input + Generator -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Nilai Token Akses Publik
                    </label>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <input type="text" name="public_access_token" value="{{ old('public_access_token', $currentToken) }}" required
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-mono text-sm font-bold tracking-widest text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">
                    Anda bebas menentukan teks token sendiri (contoh: <code>kitasehat</code> atau <code>FAKULTAS-MEDIKA</code>).
                </p>
            </div>

            <!-- Pesan Panduan Publik -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Pesan Petunjuk untuk Pengunjung
                </label>
                <textarea name="public_token_message" rows="3"
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">{{ old('public_token_message', $tokenMessage) }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Pesan ini akan tampil di layar saat pengunjung diminta memasukkan token akses.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Bagian / Unit Pengelola</label>
                    <input type="text" name="instansi_nama" value="{{ old('instansi_nama', $instansiNama) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Universitas</label>
                    <input type="text" name="universitas_nama" value="{{ old('universitas_nama', $universitasNama) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/30 transition">
                    Simpan Pengaturan
                </button>
            </div>
        </form>

        <!-- Form Terpisah Generate Token Acak -->
        <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
            <div>
                <h5 class="text-xs font-bold text-slate-800">Buat Token Acak Baru Secara Otomatis</h5>
                <p class="text-[11px] text-slate-400">Sistem akan mengacak kombinasi kode token baru yang unik.</p>
            </div>
            <form action="{{ route('admin.pengaturan-publik.generate') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                    <span>Generate Token Baru</span>
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

