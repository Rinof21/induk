@extends('layouts.public')

@section('title', 'Data Dosen Penguji - BKU Universitas Tanjungpura')

@section('content')
<div x-data="{ modalOpen: false, activePenguji: {} }">

    <!-- Header Section -->
    <div class="mb-8">
        <h2 class="text-3xl font-normal text-slate-900 tracking-tight">Data Dosen Penguji</h2>
        <p class="text-sm text-slate-500 mt-1">Daftar Penguji Ujian OSCE, Sidang Profesi, dan Skripsi/Tesis di Lingkungan Fakultas</p>
    </div>

    <!-- Kotak Pencarian & Filter -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 mb-8">
        <form method="GET" action="{{ route('publik.penguji') }}" class="space-y-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}"
                       placeholder="Cari berdasarkan Nama Penguji, NIP/NIDN, Spesialisasi, atau Asal Institusi..." 
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>

            <!-- Filter Dropdowns -->
            <div class="flex flex-wrap items-center gap-3 pt-1">
                <div class="flex items-center space-x-2 text-sm font-semibold text-slate-500 mr-2">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter:</span>
                </div>

                <select name="jenis_penguji" onchange="this.form.submit()" class="text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis Penguji</option>
                    @foreach($daftarJenisPenguji as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis_penguji') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>

                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    Terapkan
                </button>

                @if(request()->anyFilled(['cari', 'jenis_penguji', 'status']))
                    <a href="{{ route('publik.penguji') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 4 Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">TOTAL PENGUJI</p>
            <p class="text-4xl font-extrabold text-blue-600 mt-2">{{ number_format($stats['total_penguji']) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">PENGUJI AKTIF</p>
            <p class="text-4xl font-extrabold text-emerald-600 mt-2">{{ number_format($stats['total_aktif']) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">PENGUJI OSCE</p>
            <p class="text-4xl font-extrabold text-indigo-600 mt-2">{{ number_format($stats['total_osce']) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">PEMBARUAN TERAKHIR</p>
            <p class="text-sm font-semibold text-slate-700 mt-4 flex items-center">
                <i class="fa-regular fa-clock text-slate-400 mr-2"></i>
                {{ $stats['pembaruan_terakhir'] }}
            </p>
        </div>
    </div>

    <!-- Tabel Data Penguji -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 font-normal text-xs uppercase tracking-wider">
                        <th class="py-4 px-4 text-center w-14 font-normal">NO</th>
                        <th class="py-4 px-4 font-normal">NAMA LENGKAP</th>
                        <th class="py-4 px-4 font-normal">NIP / NIDN</th>
                        <th class="py-4 px-4 font-normal">SPESIALISASI / BIDANG</th>
                        <th class="py-4 px-4 font-normal">JENIS PENGUJI</th>
                        <th class="py-4 px-4 font-normal">UNIT KERJA / ASAL</th>
                        <th class="py-4 px-4 text-center font-normal">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-normal">
                    @forelse($pengujis as $index => $penguji)
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-4 px-4 text-center text-slate-500 font-normal text-sm">
                                {{ $pengujis->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-4 font-normal text-slate-800 text-[15px]">
                                <a href="javascript:void(0)" 
                                   @click="activePenguji = {{ json_encode($penguji) }}; modalOpen = true"
                                   class="hover:text-blue-600 transition leading-snug font-normal text-slate-800">
                                    {{ $penguji->nama_lengkap }}
                                </a>
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-normal text-[14px]">
                                {{ $penguji->nip_nidn ?? '-' }}
                            </td>
                            <td class="py-4 px-4 font-normal text-slate-700 text-sm">
                                {{ $penguji->spesialisasi ?? '-' }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-normal bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ $penguji->jenis_penguji }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-normal text-sm">
                                {{ $penguji->unit_kerja }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($penguji->status === 'Aktif')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-normal bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Aktif
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
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-3 text-slate-300"></i>
                                <p class="text-sm">Tidak ditemukan data penguji yang cocok dengan kriteria pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengujis->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $pengujis->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail Penguji -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalOpen" @click="modalOpen = false" class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="modalOpen" 
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
                
                <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-5 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center font-bold">
                                <i class="fa-solid fa-user-check text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold leading-tight" x-text="activePenguji.nama_lengkap"></h3>
                                <p class="text-xs text-indigo-100" x-text="activePenguji.unit_kerja"></p>
                            </div>
                        </div>
                        <button @click="modalOpen = false" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                    </div>
                </div>

                <div class="p-6 space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">NIP / NIDN</span>
                            <span class="text-slate-800 font-mono font-bold" x-text="activePenguji.nip_nidn || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Jenis Penguji</span>
                            <span class="text-slate-800 font-bold" x-text="activePenguji.jenis_penguji"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Spesialisasi</span>
                            <span class="text-slate-800 font-bold" x-text="activePenguji.spesialisasi || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">No. Sertifikat</span>
                            <span class="text-slate-800 font-mono font-bold" x-text="activePenguji.no_sertifikat || '-'"></span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Keterangan / Penugasan Khusus</span>
                        <p class="text-slate-800 font-medium mt-1 leading-relaxed" x-text="activePenguji.keterangan || 'Tidak ada keterangan tambahan.'"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Email</span>
                            <span class="text-slate-800 font-medium" x-text="activePenguji.email || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Kontak / No. Telepon</span>
                            <span class="text-slate-800 font-medium" x-text="activePenguji.no_hp || '-'"></span>
                        </div>
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

