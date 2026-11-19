@extends('layouts.public')

@section('title', 'Data Pasien Standar (PS) OSCE - Universitas Tanjungpura')

@section('content')
<div x-data="{ modalOpen: false, activePs: {} }">

    <!-- Header Section -->
    <div class="mb-8">
        <h2 class="text-3xl font-normal text-slate-900 tracking-tight">Data Pasien Standar (PS)</h2>
        <p class="text-sm text-slate-500 mt-1">Daftar Pasien Standar Terlatih untuk Simulasi Ujian OSCE dan Praktik Keterampilan Klinis Fakultas</p>
    </div>

    <!-- Kotak Pencarian & Filter -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 mb-8">
        <form method="GET" action="{{ route('publik.pasien-standar') }}" class="space-y-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}"
                       placeholder="Cari berdasarkan Kode PS, Nama, Peran Kasus, Pekerjaan, atau Catatan..." 
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>

            <!-- Filter Dropdowns -->
            <div class="flex flex-wrap items-center gap-3 pt-1">
                <div class="flex items-center space-x-2 text-sm font-semibold text-slate-500 mr-2">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter:</span>
                </div>

                <select name="status_ketersediaan" onchange="this.form.submit()" class="text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status Ketersediaan</option>
                    <option value="Tersedia" {{ request('status_ketersediaan') == 'Tersedia' ? 'selected' : '' }}>Tersedia (Siap Bertugas)</option>
                    <option value="Sedang Bertugas" {{ request('status_ketersediaan') == 'Sedang Bertugas' ? 'selected' : '' }}>Sedang Bertugas</option>
                    <option value="Tidak Aktif" {{ request('status_ketersediaan') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif / Cuti</option>
                </select>

                <select name="jenis_kelamin" onchange="this.form.submit()" class="text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis Kelamin</option>
                    <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>

                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    Terapkan
                </button>

                @if(request()->anyFilled(['cari', 'status_ketersediaan', 'jenis_kelamin']))
                    <a href="{{ route('publik.pasien-standar') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 4 Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">TOTAL PASIEN STANDAR</p>
            <p class="text-4xl font-extrabold text-blue-600 mt-2">{{ number_format($stats['total_ps']) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">TERSEDIA / SIAP</p>
            <p class="text-4xl font-extrabold text-emerald-600 mt-2">{{ number_format($stats['total_tersedia']) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">SEDANG BERTUGAS</p>
            <p class="text-4xl font-extrabold text-amber-500 mt-2">{{ number_format($stats['total_bertugas']) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">PEMBARUAN TERAKHIR</p>
            <p class="text-sm font-semibold text-slate-700 mt-4 flex items-center">
                <i class="fa-regular fa-clock text-slate-400 mr-2"></i>
                {{ $stats['pembaruan_terakhir'] }}
            </p>
        </div>
    </div>

    <!-- Tabel Data Pasien Standar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 font-normal text-xs uppercase tracking-wider">
                        <th class="py-4 px-4 text-center w-24 font-normal">KODE PS</th>
                        <th class="py-4 px-4 font-normal">NAMA LENGKAP</th>
                        <th class="py-4 px-4 text-center font-normal">GENDER</th>
                        <th class="py-4 px-4 text-center font-normal">USIA</th>
                        <th class="py-4 px-4 font-normal">PERAN KASUS YANG DIKUASAI</th>
                        <th class="py-4 px-4 text-center font-normal">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-normal">
                    @forelse($pasiens as $ps)
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-4 px-4 text-center">
                                <span class="font-normal text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200 text-xs">
                                    {{ $ps->kode_ps }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-normal text-slate-800 text-[15px]">
                                <a href="javascript:void(0)" 
                                   @click="activePs = {{ json_encode($ps) }}; modalOpen = true"
                                   class="hover:text-blue-600 transition leading-snug font-normal text-slate-800">
                                    {{ $ps->nama_lengkap }}
                                </a>
                                <p class="text-xs text-slate-400 font-normal mt-0.5">{{ $ps->pekerjaan ?? 'Wiraswasta / Umum' }}</p>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-normal {{ $ps->jenis_kelamin === 'Laki-laki' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-pink-50 text-pink-700 border border-pink-200' }}">
                                    {{ $ps->jenis_kelamin }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center text-slate-700 font-normal text-sm">
                                {{ $ps->usia ? $ps->usia . ' th' : '-' }}
                            </td>
                            <td class="py-4 px-4 text-slate-700 text-sm font-normal max-w-xs truncate">
                                {{ $ps->peran_dikuasai ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($ps->status_ketersediaan === 'Tersedia')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-normal bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Tersedia
                                    </span>
                                @elseif($ps->status_ketersediaan === 'Sedang Bertugas')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-normal bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                        Sedang Bertugas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-normal bg-slate-100 text-slate-600 border border-slate-200">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-3 text-slate-300"></i>
                                <p class="text-sm">Tidak ditemukan data Pasien Standar yang cocok dengan kriteria pencarian.</p>
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

    <!-- Modal Detail Pasien Standar -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalOpen" @click="modalOpen = false" class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="modalOpen" 
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
                
                <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-5 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center font-bold">
                                <i class="fa-solid fa-hospital-user text-lg"></i>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-white/20 text-xs px-2 py-0.5 rounded font-mono font-bold" x-text="activePs.kode_ps"></span>
                                    <h3 class="text-base font-bold leading-tight" x-text="activePs.nama_lengkap"></h3>
                                </div>
                                <p class="text-xs text-emerald-100 mt-0.5">Pasien Standar Simulasi OSCE FK UNTAN</p>
                            </div>
                        </div>
                        <button @click="modalOpen = false" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                    </div>
                </div>

                <div class="p-6 space-y-4 text-xs">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Jenis Kelamin</span>
                            <span class="text-slate-800 font-bold" x-text="activePs.jenis_kelamin"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Usia</span>
                            <span class="text-slate-800 font-bold" x-text="activePs.usia ? activePs.usia + ' Tahun' : '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Tinggi Badan</span>
                            <span class="text-slate-800 font-bold" x-text="activePs.tinggi_badan ? activePs.tinggi_badan + ' cm' : '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Berat Badan</span>
                            <span class="text-slate-800 font-bold" x-text="activePs.berat_badan ? activePs.berat_badan + ' kg' : '-'"></span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Peran / Kasus Simulasi Yang Dikuasai</span>
                        <p class="text-slate-800 font-medium mt-1 leading-relaxed" x-text="activePs.peran_dikuasai || 'Belum diisi'"></p>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Catatan Khusus / Riwayat Pelatihan</span>
                        <p class="text-slate-800 font-medium mt-1 leading-relaxed" x-text="activePs.catatan_khusus || 'Tidak ada catatan khusus.'"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Kontak / No. HP</span>
                            <span class="text-slate-800 font-medium" x-text="activePs.no_hp || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Pekerjaan</span>
                            <span class="text-slate-800 font-medium" x-text="activePs.pekerjaan || '-'"></span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Alamat Domisili</span>
                        <p class="text-slate-800 font-medium mt-0.5" x-text="activePs.alamat || '-'"></p>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 flex justify-end">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

