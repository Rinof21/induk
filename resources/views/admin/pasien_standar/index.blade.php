@extends('layouts.admin')

@section('title', 'Kelola Pasien Standar')
@section('page_title', 'Kelola Pasien Standar (PS)')

@section('content')
<div class="space-y-6" x-data="{ deleteModal: false, deleteUrl: '', deleteName: '' }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Daftar Pasien Standar (Standardized Patients)</h3>
            <p class="text-xs text-slate-500">Kelola pemeran pasien untuk simulasi ujian OSCE dan praktikum kedokteran/kesehatan</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.trash.index', ['tipe' => 'pasien_standar']) }}" 
               class="inline-flex items-center space-x-2 px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl border border-rose-200 transition">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>Kotak Sampah</span>
            </a>
            <a href="{{ route('admin.pasien-standar.create') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/30 transition">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Pasien Standar</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.pasien-standar.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[240px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}" 
                       placeholder="Cari berdasarkan Kode PS, Nama, Peran Kasus..."
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600">
            </div>

            <select name="status_ketersediaan" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700">
                <option value="">Semua Ketersediaan</option>
                <option value="Tersedia" {{ request('status_ketersediaan') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Sedang Bertugas" {{ request('status_ketersediaan') == 'Sedang Bertugas' ? 'selected' : '' }}>Sedang Bertugas</option>
                <option value="Tidak Aktif" {{ request('status_ketersediaan') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>

            <select name="jenis_kelamin" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700">
                <option value="">Semua Gender</option>
                <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
                Cari
            </button>

            @if(request()->anyFilled(['cari', 'status_ketersediaan', 'jenis_kelamin']))
                <a href="{{ route('admin.pasien-standar.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded-xl transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-16">KODE</th>
                        <th class="py-3.5 px-4">NAMA LENGKAP</th>
                        <th class="py-3.5 px-4 text-center">GENDER</th>
                        <th class="py-3.5 px-4 text-center">USIA</th>
                        <th class="py-3.5 px-4">PERAN KASUS</th>
                        <th class="py-3.5 px-4">KONTAK</th>
                        <th class="py-3.5 px-4 text-center">STATUS</th>
                        <th class="py-3.5 px-4 text-center w-28">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($pasiens as $ps)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                    {{ $ps->kode_ps }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $ps->nama_lengkap }}
                                <p class="text-[11px] text-slate-400 font-normal">{{ $ps->pekerjaan ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[11px] {{ $ps->jenis_kelamin === 'Laki-laki' ? 'bg-sky-50 text-sky-700' : 'bg-pink-50 text-pink-700' }}">
                                    {{ $ps->jenis_kelamin }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-slate-700 font-semibold">
                                {{ $ps->usia ? $ps->usia.' th' : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 max-w-xs truncate">
                                {{ $ps->peran_dikuasai ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $ps->no_hp ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($ps->status_ketersediaan === 'Tersedia')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Tersedia
                                    </span>
                                @elseif($ps->status_ketersediaan === 'Sedang Bertugas')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        Bertugas
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('admin.pasien-standar.edit', $ps) }}" 
                                       title="Edit PS"
                                       class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button" 
                                            @click="deleteModal = true; deleteUrl = '{{ route('admin.pasien-standar.destroy', $ps) }}'; deleteName = '{{ addslashes($ps->nama_lengkap) }}'"
                                            title="Hapus PS"
                                            class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                Belum ada data Pasien Standar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pasiens->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $pasiens->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div x-show="deleteModal" @click="deleteModal = false" class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl relative z-10">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900">Pindahkan ke Kotak Sampah?</h4>
                <p class="text-xs text-slate-500 mt-2">
                    Apakah Anda yakin ingin memindahkan data Pasien Standar <strong x-text="deleteName" class="text-slate-800"></strong> ke Kotak Sampah? Data dapat dipulihkan sewaktu-waktu.
                </p>

                <div class="flex items-center space-x-3 mt-6">
                    <button type="button" @click="deleteModal = false" class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-rose-600/30 transition">
                            Pindahkan ke Sampah
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

